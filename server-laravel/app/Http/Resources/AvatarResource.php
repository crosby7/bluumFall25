<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AvatarResource extends JsonResource
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
            'name' => $this->name,
            'species' => $this->species,
            'description' => $this->description,
            'base_path' => $this->base_path,
            'layer_count' => $this->layer_count,
            'is_default' => $this->is_default,
            'layers' => AvatarLayerResource::collection($this->whenLoaded('layers')),
        ];
    }
}
