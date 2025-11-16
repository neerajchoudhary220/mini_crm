<?php

namespace Database\Seeders;

use App\Models\ContactCustomField;
use Illuminate\Database\Seeder;

class CustomContactFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fields = [
            [
                'field_label' => 'Birthday',
                'field_name' => 'birthday',
                'field_type' => 'date',
            ],
            [
                'field_label' => 'Address',
                'field_name' => 'address',
                'field_type' => 'textarea',
            ]
        ];

        foreach ($fields as $field) {
            ContactCustomField::updateOrCreate(['field_name' => $field['field_name']], [
                'field_type' => $field['field_type'],
                'field_label' => $field['field_label'],
            ]);
        }
    }
}
