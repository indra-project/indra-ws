<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $fillable = [
        'name',
        'type',
        'active',
        'intervals',
        'unit',
        'station_id'
    ];

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

}