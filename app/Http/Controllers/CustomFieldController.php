<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFieldStoreRequest;
use App\Http\Requests\CustomFieldUpdateRequest;
use App\Models\ContactCustomField;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CustomFieldController extends Controller
{
    public function index()
    {
        return view('custom-field.index');
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
            $recordsTotal = ContactCustomField::query()->count();
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
                $d->action = view('custom-field.action', compact('d'))->render();
                $d->created_at = Carbon::parse($d->created_at)->format('d-M-Y');
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


    public function store(CustomFieldStoreRequest $customFieldStoreRequest)
    {
        try {
            $customFields = $customFieldStoreRequest->only('field_label', 'field_name', 'field_type', 'options');
            if ($customFieldStoreRequest->get('options') && $customFieldStoreRequest->get('field_type') === 'select') {
                $customFields['options'] = json_encode(explode(',', $customFieldStoreRequest->options));
            } else {
                $customFields['options'] = null;
            }

            DB::beginTransaction();
            ContactCustomField::create($customFields);
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'New field added successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error($e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function update(CustomFieldUpdateRequest $customFieldUpdateRequest, ContactCustomField $contactCustomField)
    {
        try {
            $customFields = $customFieldUpdateRequest->only('field_label', 'field_name', 'field_type', 'options');

            if ($customFieldUpdateRequest->get('options') && $customFieldUpdateRequest->get('field_type') === 'select') {
                $customFields['options'] = json_encode(explode(',', $customFieldUpdateRequest->options));
            } else {
                $customFields['options'] = null;
            }

            DB::beginTransaction();
            $contactCustomField->update([
                'field_label' => $customFields['field_label'],
                'field_name' => $customFields['field_name'],
                'field_type' => $customFields['field_type'],
                'options' => $customFields['options'],
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error($e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(ContactCustomField $contactCustomField)
    {
        $contactCustomField->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Deleted successfully'
        ]);
    }
    public function listFilter($request)
    {
        $request = collect($request);
        $search = $request->get('search');
        $query = ContactCustomField::query();
        return $query->when($search, fn($custom_field) => $custom_field->where('field_label', 'like', '%' . $search['value'] . '%'));
    }
}
