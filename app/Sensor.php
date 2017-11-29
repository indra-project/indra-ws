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
        'unit'
    ];

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

}