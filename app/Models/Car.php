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
        'details_id',
        'history_id',
        'inspector_id',
        'engine_id',
        'steering_id',
        'interior_id',
        'specs_id',
        'wheels_id',
        'exterior_id',
        'images',
        'status'
    ];

    protected $casts = [
        'images' => 'array',
        'latat' => 'collection'
    ];

    protected $hidden = [
        'updated_at',
        'details_id',
        'history_id',
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

    public function details(){
        return $this->hasOne(Details::class);
    }

    public function history(){
        return $this->hasOne(History::class);
    }

    public function inspector(){
        return $this->belongsTo(User::class, 'inspector_id');
    }

    public function seller(){
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    public function auction(){
        return $this->hasOne(Auction::class);
    }
}

