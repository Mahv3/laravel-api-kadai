<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id' => $this->id,
            'name' => $this->name,
            'floor' => $this->floor,
            'category' => $this->category,
            'open_time' => substr($this->open_time, 0, 5),  // "10:00:00" -> "10:00"
            'close_time' => substr($this->close_time, 0, 5), // "21:00:00" -> "21:00"
            'tel' => $this->tel,
            'description' => $this->description,
            'is_temporarily_closed' => (bool)$this->is_temporarily_closed, 
        ];
    }
}
