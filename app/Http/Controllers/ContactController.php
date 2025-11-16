<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactStoreRequest;
use App\Http\Requests\ContactUpdateRequest;
use App\Http\Requests\MergeRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Models\ContactCustomFieldValue;
use App\Models\ContactMergeLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact.index');
    }

    public function store(ContactStoreRequest $contactStoreRequest)
    {
        try {
            DB::beginTransaction();
            $contact_basic_data = $contactStoreRequest->only('name', 'email', 'phone', 'gender');
            $contact = Contact::create($contact_basic_data);

            //Store Profile Image
            if ($contactStoreRequest->hasFile('profile_image')) {
                $contact->addMedia($contactStoreRequest->file('profile_image'), 'profile_image');
            }
            //store document file
            if ($contactStoreRequest->hasFile('document')) {
                $contact->addMedia($contactStoreRequest->file('document'), 'document');
            }

            //Store custom field
            if ($contactStoreRequest->custom) {
                foreach ($contactStoreRequest->custom as $fieldId => $value) {
                    ContactCustomFieldValue::create([
                        'contact_id'      => $contact->id,
                        'custom_field_id' => $fieldId,
                        'value'           => $value,
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully added new contact'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error($e->getMessage());
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(ContactUpdateRequest $contactUpdateRequest, Contact $contact)
    {
        try {
            DB::beginTransaction();
            $contact_basic_data = $contactUpdateRequest->only('name', 'email', 'phone', 'gender');
            //Update basic details
            $contact->update([
                'name' => $contact_basic_data['name'],
                'email' => $contact_basic_data['email'],
                'phone' => $contact_basic_data['phone'],
                'gender' => $contact_basic_data['gender'],
            ]);

            //update files
            if ($contactUpdateRequest->hasFile('profile_image')) {
                $oldProfileImage = $contact->media()->where('tag', 'profile_image')->first();
                if ($oldProfileImage) {
                    $contact->deleteMedia($oldProfileImage);
                }
                $contact->addMedia($contactUpdateRequest->file('profile_image'), 'profile_image');
            }
            if ($contactUpdateRequest->hasFile('document')) {
                $oldDocument = $contact->media()->where('tag', 'document')->first();
                if ($oldDocument) {
                    $contact->deleteMedia($oldDocument);
                }
                $contact->addMedia($contactUpdateRequest->file('document'), 'document');
            }

            //Update custom fields
            if ($contactUpdateRequest->custom) {
                $contactUpdateRequest->custom->delete(); //delete old custom fields
                foreach ($contactUpdateRequest->custom as $fieldId => $value) {
                    ContactCustomFieldValue::create(
                        [
                            'contact_id' => $contact->id,
                            'custom_field_id' => $fieldId,
                            'value' => $value,
                        ],
                    );
                }
            }
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Contact updated successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error($e->getMessage());
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $_order = request('order');
            $_columns = request('columns');
            $order_by = $_columns[$_order[0]['column']]['name'];
            $order_dir = $_order[0]['dir'];
            $skip = request('start');
            $take = request('length');
            $recordsTotal = Contact::query()->count();
            $data = self::listFilter($request)
                ->orderBy($order_by, $order_dir)
                ->orderBy('id', 'DESC')
                ->skip($skip)
                ->take($take)
                ->get();

            $recordsFiltered = self::listFilter($request)->count();
            $idx = 1;
            foreach ($data as $d) {
                $d->idx = $idx;
                $d->name = $d->is_active ? $d->name : "<i class='bi bi-check-circle-fill text-success' title='Merged'></i> <span>$d->name</span>";
                $d->action = Blade::render(
                    '<x-action-buttons  :edit-url="$editUrl" :update-url="$updateUrl" 
                     :merge-simple-list-url="$mergeSimpleListUrl" :contact-id="$contactId" :merge-log-url="$mergeLogUrl"/>',
                    [
                        'editUrl' => route('contacts.edit', $d),
                        'updateUrl' => route('contacts.update', $d),
                        'mergeSimpleListUrl' => $d->is_active ? route('contacts.simplelist', $d->id) : null,
                        'contactId' => $d->is_active ?  $d->id : null,
                        'mergeLogUrl' => !$d->is_active ? route('contacts.merge.log', $d) : null,

                    ]
                );

                $d->created_at_display = Carbon::parse($d->created_at)->format('d-M-Y');
                $idx++;
            }
            return [
                'draw' => intval($request->get('draw')),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
            ];
        }
    }

    public function simpleList(Request $request)
    {
        if ($request->ajax()) {
            $page   = $request->page ?? 1;
            $limit  = 10;
            $offset = ($page - 1) * $limit;
            $query = Contact::query();
            $q = $request->get('q');
            $items = $query->forActive()
                ->when($request->id, function ($contact) use ($request) {
                    $contact->where('id', '!=', $request->id);
                })
                ->when($q, function ($contact) use ($q) {
                    $contact->where(function ($sub) use ($q) {
                        $sub->where('name', 'like', "%$q%")
                            ->orWhere('email', 'like', "%$q%")
                            ->orWhere('phone', 'like', "%$q%");
                    });
                })
                ->orderBy('id', 'DESC')
                ->offset($offset)
                ->limit($limit)
                ->get();

            $totalCount = $query->forActive()->count();
            return response()->json([
                "results" => $items->map(function ($item) {
                    return [
                        "id" => $item->id,
                        "text" => $item->name . " (" . $item->email . ")"
                    ];
                }),
                "pagination" => [
                    "more" => ($offset + $limit) < $totalCount
                ]
            ]);
        }
    }

    public function listFilter($request)
    {
        $request = collect($request);
        $search = $request->get('search');
        $query = Contact::query();

        return $query->forActive()
            ->when($search, fn($contact) => $contact->where('name', 'like', '%' . $search['value'] . '%'))
            ->orWhere('email', 'like', '%' . $search['value'] . '%')
            ->orWhere('phone', 'like', '%' . $search['value'] . '%');
    }

    public function edit(Contact $contact)
    {
        return new ContactResource($contact->load(['media', 'customFieldValues.customField']));
    }

    private function mergeContacts(Contact $master, Contact $secondary, string $policy = "keep_master",): array
    {
        if ($master->id === $secondary->id) {
            throw new \Exception('Cannot merge the same contact.');
        }
        return DB::transaction(function () use ($master, $secondary, $policy) {
            $log = [
                'master_id' => $master->id,
                'secondary_id' => $secondary->id,
                'merged_fields' => [],
                'conflicts' => [],
                'media_copied' => [],
                'timestamp' => now()->toDateTimeString(),
            ];
            //Standard fields (name,email,phone,gender)
            $standardFields = ['name', 'email', 'phone', 'gender'];
            foreach ($standardFields as $field) {
                $mVal = $master->{$field};
                $sVal = $secondary->{$field};
                // copy missing fields
                if ((empty($mVal) || $mVal === null) && !empty($sVal)) {
                    $master->{$field} = $sVal;
                    $log['merged_fields'][] = "copied_field:{$field}";
                } elseif (!empty($mVal) && !empty($sVal) && $mVal !== $sVal) {
                    // conflict - handle specially for email & phone
                    if ($policy === 'append' && in_array($field, ['email', 'phone'])) {
                        // Avoid duplicate when already present in master
                        $masterParts = array_filter(array_map('trim', preg_split('/[;|,]/', (string)$mVal)));
                        $sParts = array_filter(array_map('trim', preg_split('/[;|,]/', (string)$sVal)));
                        foreach ($sParts as $p) {
                            if (!in_array($p, $masterParts) && $p !== '') {
                                $masterParts[] = $p;
                            }
                        }

                        $newVal = implode(';', $masterParts);
                        $master->{$field} = $newVal;
                        $log['merged_fields'][] = "appended_field:{$field}";
                    } else {
                        // keep master but log conflict
                        $log['conflicts'][] = "{$field}:master_kept";
                    }
                }
            }
            $master->save();

            //Custom fields
            $secondaryValues = $secondary->customFieldValues()->get();
            foreach ($secondaryValues as $sv) {
                $existing = $master->customFieldValues()->where('custom_field_id', $sv->custom_field_id)->first();

                if (!$existing) {
                    // Duplicate the value for master (preserve secondary)
                    ContactCustomFieldValue::create([
                        'contact_id' => $master->id,
                        'custom_field_id' => $sv->custom_field_id,
                        'value' => $sv->value,
                    ]);
                    $log['merged_fields'][] = "custom_added:" . $sv->custom_field_id;
                } else {
                    if ((string)$existing->value !== (string)$sv->value) {
                        if ($policy === 'append') {
                            // append without duplicates
                            $existingParts = array_filter(array_map('trim', explode(' | ', (string)$existing->value)));
                            $svParts = array_filter(array_map('trim', explode(' | ', (string)$sv->value)));

                            foreach ($svParts as $p) {
                                if (!in_array($p, $existingParts) && $p !== '') {
                                    $existingParts[] = $p;
                                }
                            }
                            $existing->value = implode(' | ', $existingParts);
                            $existing->save();
                            $log['merged_fields'][] = "custom_appended:" . $sv->custom_field_id;
                        } else {
                            // keep master value
                            $log['conflicts'][] = "custom_{$sv->custom_field_id}:master_kept";
                        }
                    }
                }
            }

            // Media: duplicate secondary media and assign duplicates to master (so no data lost)
            // Assumes media relation name 'media' and media has file_path, disk, file_name, mime_type, size, tag/collection_name
            $secondaryMedias = $secondary->media()->get();
            foreach ($secondaryMedias as $m) {
                try {
                    // $disk = $m->disk ?? 'public';
                    $oldPath = $m->file_path; // adjust to your column name
                    if ($oldPath && Storage::exists($oldPath)) {
                        $folder = pathinfo($oldPath, PATHINFO_DIRNAME);
                        $name = pathinfo($oldPath, PATHINFO_BASENAME);
                        $newName = pathinfo($name, PATHINFO_FILENAME) . '_copy_' . time() . '.' . pathinfo($name, PATHINFO_EXTENSION);
                        $newPath = $folder . '/' . $newName;

                        Storage::copy($oldPath, $newPath);

                        // create new media record for master (duplicate)
                        $newMedia = $master->media()->create([
                            'file_name' => $m->file_name,
                            'file_path' => $newPath,
                            'mime_type' => $m->mime_type,
                            'tag' => ($m->tag),
                        ]);

                        $log['media_copied'][] = $newMedia->id;
                    } else {
                        // If file missing just re-link metadata (rare)
                        $newMedia = $master->media()->create($m->toArray());
                        $log['media_copied'][] = $newMedia->id;
                    }
                } catch (\Throwable $ex) {
                    // do not fail entire merge just log
                    $log['media_errors'][] = "media_copy_failed:{$m->id} " . $ex->getMessage();
                }
            }

            //Mark secondary as merged/inactive and preserve pointer
            $secondary->is_active = false;
            $secondary->merged_into = $master->id;
            $secondary->save();

            //Save merge log (snapshot + merged details)
            $mergedSnapshot = [
                'master' => $master->toArray(),
                'secondary' => $secondary->toArray(),
                'secondary_custom_values' => $secondaryValues->map->toArray(),
                'merged_fields' => $log['merged_fields'],
                'conflicts' => $log['conflicts'],
                'media_copied' => $log['media_copied'] ?? [],
            ];

            $mergeLog = ContactMergeLog::create([
                'master_contact_id' => $master->id,
                'merged_contact_id' => $secondary->id,
                'merged_data' => $mergedSnapshot,
            ]);

            $log['merge_log_id'] = $mergeLog->id;
            return $log;
        });
    }
    public function merge(MergeRequest $mergeRequest)
    {
        try {
            $primaryId = (int)$mergeRequest->primary_id;
            $secondaryId = (int)$mergeRequest->secondary_id;
            $masterChoice = $mergeRequest->master;
            $policy = $mergeRequest->policy ?? 'keep_master';

            $primary = Contact::with(['customFieldValues', 'media'])->findOrFail($primaryId);
            $secondary = Contact::with(['customFieldValues', 'media'])->findOrFail($secondaryId);

            // Determine master & slave
            $master = $masterChoice === 'primary' ? $primary : $secondary;
            $slave  = $masterChoice === 'primary' ? $secondary : $primary;

            if ($master->id === $slave->id) {
                return response()->json(['status' => 'failed', 'message' => 'Select two different contacts'], 400);
            }
            $log = $this->mergeContacts($master, $secondary, $policy);
            return response()->json([
                'status' => 'success',
                'message' => 'Contacts merged successfully',
                'data' => $log
            ]);
        } catch (\Exception $e) {
            logger()->error('Merge error: ' . $e->getMessage());
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function mergeLogView(ContactMergeLog $contactMergeLog)
    {
        $log = $contactMergeLog->load(['masterContact', 'mergedContact']);
        $data = $log->merged_data;
        return view('contact.merge-log', compact('log', 'data'));
    }
}
