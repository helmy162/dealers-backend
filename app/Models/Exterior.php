<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exterior extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
        'car_id',
        'markers'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'car_id',
        'id'
    ];

    protected $casts = [
        'markers' => 'array'
    ];

    public function car(){
        $this->belongsTo(Car::class);
    }
}
