<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'active',
        'mac_address'
    ];

    public function sensors()
    {
        return $this->hasMany(Sensor::class);
    }

}