<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact.index');
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
                $d->action = "--";
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

    public function listFilter($request)
    {
        $request = collect($request);
        $search = $request->get('search');
        $query = Contact::query();
        return $query->when($search, fn($contact) => $contact->where('name', 'like', '%' . $search['value'] . '%'));
    }
}
