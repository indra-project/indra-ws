<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    protected $fillable = [
        'value',
        'date',
        'altitude',
        'latitude',
        'longitude'
    ];

}