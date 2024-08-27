<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "price",
        "location",
        "description",
        "bedrooms",
        "bathrooms",
        "for_rent",
        "max_tenants",
        "min_contract_time",
        "accept_animals"
    ];

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }
}
