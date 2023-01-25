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
}
