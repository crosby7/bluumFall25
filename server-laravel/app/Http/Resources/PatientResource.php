<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'pairing_code' => $this->pairing_code,
            'paired_at' => $this->paired_at,
            'avatar_id' => $this->avatar_id,
            'experience' => $this->experience,
            'gems' => $this->gems,
            'device_identifier' => $this->device_identifier,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
