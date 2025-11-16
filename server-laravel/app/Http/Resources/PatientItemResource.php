<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'patient_id' => $this->patient_id,
            'item_id' => $this->item_id,
            'equipped' => $this->equipped,
            'item' => [
                'id' => $this->item->id,
                'name' => $this->item->name,
                'description' => $this->item->description,
                'price' => $this->item->price,
                'image' => $this->item->image,
                'category' => $this->item->category,
            ],
        ];
    }
}
