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

        $data = $request->all();

        $limit = $data['limit'] ?? 20;

        $order = $data['order'] ?? null;
        if ($order !== null) {
            $order = explode(',', $order);
        }
        $order[0] = $order[0] ?? 'id';
        $order[1] = $order[1] ?? 'asc';

        $where = $data['where'] ?? [];

        $like = null;
        if (!empty($data['like']) and is_array($data['like'])) {
            $like = $data['like'];

            $key = key(reset($like));
            $like[0] = $key;
            $like[1] = '%'.$like[$key].'%';
        }

        $results = $this->model
            ->orderBy($order[0], $order[1])
            ->where(function ($query) use ($like) {
                if ($like) {
                    return $query->where($like[0], 'like', $like[1]);
                }
                return $query;
            })
            ->whereStationId($station->id)
            ->where($where)
            ->paginate($limit);

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
        $this->validate($request, $this->rules ?? [], $this->messages ?? []);


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

}