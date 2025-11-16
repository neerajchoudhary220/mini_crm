<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ContactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $profileImage = $this->media()->where('tag', 'profile_image')->first();
        $document = $this->media()->where('tag', 'document')->first();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'profile_image' => $profileImage
                ? Storage::url($profileImage->file_path)
                : "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSnkcqkUHsDulyGaMQk4mV7s9_d8-FW0x8ZOQ&s",
            'document' => $document
                ? Storage::url($document->file_path)
                : null,
            'custom_fields' => CustomFieldResource::collection($this->customFieldValues),
        ];
    }
}
