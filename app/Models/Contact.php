<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'gender', 'profile_image_id', 'merged_into'];

    public function documents()
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}
