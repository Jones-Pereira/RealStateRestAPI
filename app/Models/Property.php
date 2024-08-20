<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'price', 'address', 'city_id', 'zip_code', 'type', 'status',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
