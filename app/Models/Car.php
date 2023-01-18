<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
        'inspector_id',
        'images',
    ];

    protected $casts = [
        'engine' => 'array',
        'images' => 'array',
        'is_new' => 'boolean',
        'first_owner' => 'boolean',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'inspector_id',
        'engine_id',
        'steering_id',
        'interior_id',
        'specs_id',
        'wheels_id',
        'exterior_id',
    ];

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function engineTransmission(){
        return $this->hasOne(Engine::class);
    }

    public function exterior(){
        return $this->hasOne(Exterior::class);
    }

    public function interior(){
        return $this->hasOne(Interior::class);
    }

    public function specs(){
        return $this->hasOne(Specs::class);
    }

    public function steering(){
        return $this->hasOne(Steering::class);
    }

    public function wheels(){
        return $this->hasOne(Wheels::class);
    }

}


