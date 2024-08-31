<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "price" => $this->price,
            "location" => $this->location,
            "description" => $this->description,
            "bedrooms" => $this->bedrooms,
            "bathrooms" => $this->bathrooms,
            "for_rent" => $this->for_rent,
            "max_tenants" => $this->max_tenants,
            "min_contract_time" => $this->min_contract_time,
            "accept_animals" => $this->accept_animals,
            "created_at" => Carbon::make($this->created_at)->format("d/m/Y"),
            "images_links" =>  ImageResource::collection($this->images),
            "post_by" => $this->user->name
        ];
    }
}
