<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $hidden = [
        // 'created_at',
        'updated_at',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function dealer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}
