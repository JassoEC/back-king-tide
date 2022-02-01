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

    public function find($id)
    {
        return $this
            ->model
            ->find($id);
    }

    public function save(Model $model)
    {
        $model->save();
        return $model;
    }

    public function getPaginatedData($perPage = 10)
    {
        return $this
            ->model
            ->paginate($perPage);
    }
}
