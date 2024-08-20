<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['property_id', 'url', 'description'];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
