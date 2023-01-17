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
        'status',
        'images',
        'engine_status',
        'steering_status',
        'interior_status',
        'specs_status',
        'wheels_status',
        'exterior_status',
        'images_status'
    ];

    protected $casts = [
        'engine' => 'array',
        'markers' => 'array',
        'images' => 'array',
        'is_new' => 'boolean',
        'first_owner' => 'boolean',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'engine_status',
        'steering_status',
        'interior_status',
        'specs_status',
        'wheels_status',
        'exterior_status',
        'images_status'
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


