<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

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
            'open_time' => Carbon::parse($this->open_time)->format('H:i'),
            'close_time' => Carbon::parse($this->close_time)->format('H:i'),
            'tel' => $this->tel,
            'description' => $this->description,
            'is_temporarily_closed' => (bool)$this->is_temporarily_closed, 
        ];
    }
}
