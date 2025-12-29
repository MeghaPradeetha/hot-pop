<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function newQuery()
    {
        return $this->model->newQuery();
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(Model $model, array $data)
    {
        $model->fill($data);
        $model->save();
        return $model;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }
    
    // Stub for compatibility if needed
    protected function uploadFilesFromRequest($request, &$model)
    {
        // TODO: Implement native file upload if required
    }
}
