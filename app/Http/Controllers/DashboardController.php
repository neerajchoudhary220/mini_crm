<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactCustomField;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {

        return view("dashboard.index", [
            'totalContacts' => Contact::count(),
            'totalMergedContacts' => Contact::whereNotNull('merged_into')->count(),
            'totalCustomFields' => ContactCustomField::count(),
        ]);
    }
}
