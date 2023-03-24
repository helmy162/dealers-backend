<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specs extends Model
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
        'Fog_Lights' => 'boolean',
        'Parking_Sensor' => 'boolean',
        'Winch' => 'boolean',
        'Roof_Rack' => 'boolean',
        'Spoiler' => 'boolean',
        'Dual_Exhaust' => 'boolean',
        'Alarm' => 'boolean',
        'Rear_Video' => 'boolean',
        'Premium_Sound' => 'boolean',
        'Heads_Up_Display' => 'boolean',
        'Aux_Audio' => 'boolean',
        'Bluetooth' => 'boolean',
        'Climate_Control' => 'boolean',
        'Keyless_Entry' => 'boolean',
        'Keyless_Start' => 'boolean',
        'Leather_Seats' => 'boolean',
        'Racing_Seats' => 'boolean',
        'Cooled_Seats' => 'boolean',
        'Heated_Seats' => 'boolean',
        'Power_Seats' => 'boolean',
        'Power_Locks' => 'boolean',
        'Power_Mirros' => 'boolean',
        'Power_Windows' => 'boolean',
        'Memory_Seats' => 'boolean',
        'View_Camera' => 'boolean',
        'Blind_Spot_Indicator' => 'boolean',
        'Anti_Lock' => 'boolean',
        'Cruise_Control' => 'boolean',
        'Power_Steering' => 'boolean',
        'Front_Airbags' => 'boolean',
        'Side_Airbags' => 'boolean',
        'Night_Vision' => 'boolean',
        'Lift_Kit' => 'boolean',
        'Park_Assist' => 'boolean',
        'Adaptive_Suspension' => 'boolean',
        'Height_Control' => 'boolean',
        'Navigation_System' => 'boolean',
        'N29_System' => 'boolean',
        'Side_Steps' => 'boolean',
        'Power_Mirrors' => 'boolean',
        'Adaptive_Cruise_Control' => 'boolean',
        'Carbon_Fiber_Interior' => 'boolean',
        'Line_Change_Assist' => 'boolean'
    ];

    public function car(){
        $this->belongsTo(Car::class);
    }
}
