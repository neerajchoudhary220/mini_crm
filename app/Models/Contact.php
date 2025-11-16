<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Contact extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'gender', 'profile_image_id', 'merged_into', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean'
    ];


    protected function gender(): Attribute
    {
        return Attribute::make(get: fn(string $gender) => ucfirst($gender));
    }


    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function addMedia($file, $tag = 'profile_image')
    {
        // $path = $file->store('uploads/contacts', 'public');
        $path = Storage::put('contacts/profile', $file);
        return $this->media()->create([
            'file_name' => basename($path),
            'file_path' => $path,
            'mime_type' => $file->getClientMimeType(),
            'tag' => $tag
        ]);
    }

    public function deleteMedia($old_file)
    {
        if (Storage::exists($old_file->file_path)) {
            Storage::delete($old_file->file_path);
        }
        $old_file->delete();
    }

    public function customFieldValues()
    {
        return $this->hasMany(ContactCustomFieldValue::class, 'contact_id');
    }

    public function scopeForActive($query)
    {
        return $query->where('is_active', true);
    }
}
