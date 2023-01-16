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
        'status'
    ];

    protected $casts = [
        'engine' => 'array',
        'is_new' => 'boolean',
        'first_owner' => 'boolean',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}


