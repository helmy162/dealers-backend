<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'company',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'email_verified_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'notify_new_auction' => 'boolean',
        'notify_won_auction' => 'boolean'
    ];

    //get the user's cars
    public function cars()
    {
        return $this->hasMany(Car::class, 'inspector_id');
    }

    //add realtion bid between user and car
    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function getCarImagesAttribute()
    {
        return $this->cars->carImages;
    }  

    //get the cars data
    public function getCarsAttribute()
    {
        return $this->cars()->get();
    }

    //get users type (admin or inspecter or dealer) from users table
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function sendPasswordResetNotification($token){
        $url = env('FE_RESET_PASSWORD').'?token='.$token.'&email='.$this->email;

        $this->notify(new ResetPasswordNotification($url));
    }
}
