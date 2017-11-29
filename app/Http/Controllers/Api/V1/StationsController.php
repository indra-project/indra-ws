<?php
/**
 * Created by PhpStorm.
 * User: davil
 * Date: 27/11/2017
 * Time: 20:23
 */

namespace App\Http\Controllers\Api\V1;

use App\Station;
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

    protected $relationships = ["sensors"];

    public function __construct( Station $model)
    {
        $this->model = $model;
    }

    public function index(Request $request)
    {

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
            ->where($where)
            ->with($this->relationships())
            ->paginate($limit);

        return response()->json($results);

    }

    public function show($id)
    {
        $result = $this->model
            ->with($this->relationships())
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
        $this->validate($request, $this->rules ?? [], $this->messages ?? []);
        $result = $this->model->findOrFail($id);
        $result->update($request->all());
        return response()->json($result);
    }

    public function destroy($id){
        $result = $this->model->findOrFail($id);
        $result->delete();
        return response()->json($result);
    }

    protected function relationships()
    {
        if (isset($this->relationships)) {
            return $this->relationships;
        }
        return [];
    }

}