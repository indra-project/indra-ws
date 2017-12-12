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
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

class StationsController extends Controller
{
    protected $model;
    protected $rules = [
        'name' => 'required',
        'description' => 'required',
        'type' => 'required',
        'active' => 'required',
        'mac_address' => 'required'
    ];
    protected $messages = [
        'required' => ':attribute é obrigatório!'
    ];

    public function __construct( Station $model)
    {
        $this->model = $model;
    }

    public function index(Request $request)
    {

        $results = $this->model->get();

        return response()->json($results);

    }

    public function show($id)
    {
        $result = $this->model
            ->findOrFail($id);
        return response()->json($result);
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules ?? [], $this->messages ?? []);
        $result = $this->model->create($request->all());
        return response()->json($result);
    }

    public function update(Request $request, $id)
    {
        //$this->validate($request, $this->rules ?? [], $this->messages ?? []);
        $result = $this->model->findOrFail($id);
        $result->update($request->all());
        return response()->json($result);
    }

    public function sensor_data(Request $request, $mac)
    {
        $station = $this->model->whereMacAddress($mac)->firstOrFail();

        $startDate = $request->get('startDate', date('Y-m-d 00:00:00'));
        $endDate = $request->get('endDate', date('Y-m-d 23:59:59'));

       // dd($startDate);
       // dd($startDate);

        $sensors = Sensor::whereStationId($station->id)
            ->with(['data' => function ($data) use ($startDate, $endDate) {
                $data
                    ->whereDate('date', '>=', $startDate)
                    ->whereDate('date', '<=', $endDate);
            }])
            ->get();

        return response()->json($sensors);
    }

    public function destroy($id){
        $result = $this->model->findOrFail($id);
        $result->delete();
        return response()->json($result);
    }

    public function config($mac)
    {
        $station = $this->model->whereMacAddress($mac)->firstOrFail();
        $sensors = Sensor::whereStationId($station->id)->get();
        $result = [
            'active' => $station->active
        ];

        foreach ($sensors as $sensor) {
            $result['sensors'][$sensor->type] = [
                'active' => $sensor->active,
                'intervals' => $sensor->intervals
            ];
        }

        return response()->json($result);
    }

}