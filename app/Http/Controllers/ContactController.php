<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactStoreRequest;
use App\Http\Requests\ContactUpdateRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Models\ContactCustomFieldValue;
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
                $d->action = Blade::render(
                    '<x-action-buttons  :edit-url="$editUrl" :update-url="$updateUrl" 
                     :merge-simple-list-url="$mergeSimpleListUrl" :contact-id="$contactId"/>',
                    [
                        'editUrl' => route('contacts.edit', $d),
                        'updateUrl' => route('contacts.update', $d),
                        'mergeSimpleListUrl' => route('contacts.simplelist'),
                        'contactId' => $d->id,
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

    public function simpleList()
    {
        return Contact::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
    }

    public function listFilter($request)
    {
        $request = collect($request);
        $search = $request->get('search');
        $query = Contact::query();
        return $query->when($search, fn($contact) => $contact->where('name', 'like', '%' . $search['value'] . '%'))
            ->orWhere('email', 'like', '%' . $search['value'] . '%')
            ->orWhere('phone', 'like', '%' . $search['value'] . '%');
    }

    public function edit(Contact $contact)
    {
        return new ContactResource($contact->load(['media', 'customFieldValues.customField']));
    }

    public function merge(Request $request)
    {
        try {
            $primaryId = $request->primary_id;
            $secondaryId = $request->secondary_id;
            $masterType = $request->master; // primary or secondary

            $primary = Contact::with('customFieldValues')->findOrFail($primaryId);
            $secondary = Contact::with('customFieldValues')->findOrFail($secondaryId);
            // Determine master
            $master = $masterType == 'primary' ? $primary : $secondary;
            $slave  = $masterType == 'primary' ? $secondary : $primary;
            DB::beginTransaction();
            foreach ($slave->customFieldValues as $field) {
                $existing = $master->customFieldValues
                    ->where('custom_field_id', $field->custom_field_id)
                    ->first();

                if (!$existing) {
                    // Copy custom field to master
                    ContactCustomFieldValue::create([
                        'contact_id' => $master->id,
                        'custom_field_id' => $field->custom_field_id,
                        'value' => $field->value
                    ]);
                }
            }
            $masterProfile = $master->media()->where('tag', 'profile_image')->first();
            $slaveProfile = $slave->media()->where('tag', 'profile_image')->first();

            if (!$masterProfile && $slaveProfile) {
                $new_file = Storage::copy($slaveProfile->file_path, $slaveProfile->file_path . '_copy');
                logger()->info($new_file);
                $master->media()->create([
                    'file_name' => $slaveProfile->file_name,
                    'file_path' => $slaveProfile->file_path,
                    'mime_type' => $slaveProfile->mime_type,
                    'tag' => 'profile_image'
                ]);
            }

            $slave->merged_into = $master->id;
            $slave->save();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Contacts merged successfully'
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
}
