<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseRepository
{

    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function find($id, $with = [])
    {
        return $this
            ->model
            ->with($with)
            ->findOrFail($id);
    }

    public function save(Model $model)
    {
        $model->save();
        return $model;
    }

    public function getPaginatedData($with = [], $perPage = 10)
    {
        return $this
            ->model
            ->with($with)
            ->paginate($perPage);
    }

    public function delete(Model $model)
    {
        $model->delete();
        return $model;
    }
}
