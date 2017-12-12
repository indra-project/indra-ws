<?php
/**
 * Created by PhpStorm.
 * User: davil
 * Date: 27/11/2017
 * Time: 20:23
 */

namespace App\Http\Controllers\Api\V1;

use App\Sensor;
use App\SensorData;
use App\Station;
use Illuminate\Http\Request;
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

    public function __construct( Sensor $model)
    {
        $this->model = $model;
    }

    public function index(Request $request, $mac)
    {
        $station = Station::whereMacAddress($mac)->firstOrFail();

        $sensors = $this->model->whereStationId($station->id)->get();

        $results = [];

        foreach ($sensors as $sensor) {

            $lastData = SensorData::whereSensorId($sensor->id)->orderBy('date', 'DESC')->first();

            $results[] = [
                'id' => $sensor->id,
                'name' => $sensor->name,
                'type' => $sensor->type,
                'station_id' => $sensor->station_id,
                'active' => $sensor->active,
                'intervals' => $sensor->intervals,
                'unit' => $sensor->unit,
                'value' => ($lastData === null) ? null : $lastData->value,
            ];

        }

        return response()->json($results);

    }

    public function show($mac, $type)
    {
        $station = Station::whereMacAddress($mac)->firstOrFail();
        $result = $this->model
            ->whereStationId($station->id)
            ->whereType($type)
            ->firstOrFail();
        return response()->json($result);
    }

    public function store(Request $request, $mac)
    {

        $station = Station::whereMacAddress($mac)->firstOrFail();
        $this->validate($request, $this->rules ?? [], $this->messages ?? []);

        $fields = [
            'name' => $request->get('name'),
            'type' => $request->get('type'),
            'station_id' => $station->id,
            'active' => $request->get('active'),
            'intervals' => $request->get('intervals'),
            'unit' => $request->get('unit')
        ];

        $result = $this->model->create($fields);

        return response()->json($result);
    }

    public function update(Request $request, $mac, $type)
    {
        $station = Station::whereMacAddress($mac)->firstOrFail();
        $result = $this->model
            ->whereStationId($station->id)
            ->whereType($type)
            ->firstOrFail();
        //$this->validate($request, $this->rules ?? [], $this->messages ?? []);


        $result->update($request->all());
        return response()->json($result);
    }

    public function destroy($mac, $type){
        $station = Station::whereMacAddress($mac)->firstOrFail();
        $result = $this->model
            ->whereStationId($station->id)
            ->whereType($type)
            ->firstOrFail();
        $result->delete();
        return response()->json($result);
    }

    public function data(Request $request, $mac, $type)
    {
        $station = Station::whereMacAddress($mac)->firstOrFail();
        $sensor = $this->model
            ->whereStationId($station->id)
            ->whereType($type)
            ->firstOrFail();

        $fields = [
            'value' => $request->get('value'),
            'date' => $request->get('date'),
            'altitude' => $request->get('altitude'),
            'latitude' => $request->get('latitude'),
            'longitude' => $request->get('longitude'),
            'sensor_id' => $sensor->id
        ];

        $result = SensorData::create($fields);

        return response()->json($result);
    }

}