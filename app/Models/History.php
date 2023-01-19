<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
        'car_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'car_id',
        'id'
    ];

    protected $casts = [
        'ownership' => 'boolean',
    ];
    
    public function car(){
        $this->belongsTo(Car::class);
    }
}
