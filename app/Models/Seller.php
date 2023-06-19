<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    public const SOURCE_MANUAL = 'manual';

    public const SOURCE_PIPEDRIVE = 'pipedrive';

    protected $guarded = [];

    protected $hidden = [
        'updated_at',
    ];
}
