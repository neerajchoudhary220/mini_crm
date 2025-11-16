<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ContactCustomField extends Model
{
    protected $fillable = ['field_name', 'field_label', 'field_type', 'options'];
    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean'
    ];


    protected function fieldType(): Attribute
    {
        return Attribute::make(get: fn(string $field_type) => ucfirst($field_type));
    }

    public function values()
    {
        return $this->hasMany(ContactCustomFieldValue::class, 'custom_field_id');
    }
}
