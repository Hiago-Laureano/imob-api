<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        "property_id",
        "original_name",
        "link"
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
