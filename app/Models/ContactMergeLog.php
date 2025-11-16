<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMergeLog extends Model
{
    protected $fillable = ['master_contact_id', 'merged_contact_id', 'merged_data'];
    protected $casts = ['merged_data' => 'array'];
}
