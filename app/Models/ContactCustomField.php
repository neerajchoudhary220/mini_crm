<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactCustomField extends Model
{
    protected $fillable = ['field_name', 'field_label', 'field_type', 'options', 'created_by'];
    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean'
    ];

    public function values()
    {
        return $this->hasMany(ContactCustomFieldValue::class, 'custom_field_id');
    }
}
