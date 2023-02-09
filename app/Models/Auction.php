<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;
    
    protected $dates = [
        'created_at',
        'updated_at',
        'start_at',
        'end_at'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'duration'
    ];

    public function bids(){
        return $this->hasMany(Bid::class)->orderBy('bid', 'desc');
    }

    public function latestBid(){
        return $this->hasOne(Bid::class)->orderBy('bid', 'desc');
    }
}
