<?php
/**
 * Created by PhpStorm.
 * User: davil
 * Date: 27/11/2017
 * Time: 20:23
 */

namespace App\Http\Controllers\Api\V1;

use App\Sensor;
use App\Station;
use Laravel\Lumen\Routing\Controller;

class SensorController extends Controller
{

    protected $model;
    protected $rules = [
        'name' => 'required',
        'type' => 'required',
        'active' => 'required',
        'intervals' => 'required',
        'unit' => 'required'
    ];
    protected $messages = [
        'required' => ':attribute é obrigatório!'
    ];

    protected $relationships = ["sensors"];

    public function __construct( Sensor $model)
    {
        $this->model = $model;
    }

}