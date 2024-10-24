<?php

namespace App\Repositories;

use App\Models\Base;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseRepository
 * @package App\Repositories
 */
class BaseRepository implements BaseRepositoryInterface
{
    protected $model;
    
    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function pagination(
        array $column = ['*'], 
        array $condition = [],
        int $perpage = 1,
        array $extend = [],
        array $orderBy = ['id', 'DESC'],
        array $join = [],
        array $relations = [],
        array $rawQuery = [],
    ) {
        $query = $this->model->select($column);
        return $query
                    ->keyword($condition['keyword'] ?? null)
                    ->publish($condition['publish'] ?? null)
                    ->relationCount($relations ?? null)
                    ->customWhere($condition['where'] ?? null)
                    ->customWhereRaw($rawQuery['whereRaw'] ?? null)
                    ->customJoin($join ?? null)
                    ->customGroupBy($extend['groupBy'] ?? null)
                    ->customOrderBy($orderBy['orderBy'] ?? null)
                    ->paginate($perpage)
                    ->withQueryString()->withPath(config('app.url').$extend['path']);
    }

    public function create(array $payload = []) {
        $model = $this->model->create($payload);
        return $model->fresh();
    }

    public function update(int $id = 0, array $payload = []) {
        $model = $this->findById($id);
        return $model->update($payload);
    }

    public function updateByWhereIn(
        string $whereInField = '',
        array $whereIn = [],
        array $payload = [],
    ) {
        return $this->model->whereIn($whereInField, $whereIn)->update($payload);
    }

    public function updateByWhere(array $condition = [], array $payload = []) {
        $query = $this->model->newQuery();
        foreach ($condition as $key => $val) {
            $query->where($val[0], $val[1], $val[2]);
        }
        return $query->update($payload);
    }

    public function delete(int $id = 0) {
        return $this->findById($id)->delete();
    }

    public function forceDelete(int $id = 0) {
        return $this->findById($id)->forceDelete();
    }

    public function all() {
        return $this->model->all();
    }

    public function findById(
        int $modelId,
        array $column = ['*'],
        array $relation = []
    ) {
        return $this->model->select($column)->with($relation)->findOrFail($modelId);
    }
    
    public function createPivot($model, array $payload = [], string $relation = '') {
        return $model->{$relation}()->attach($model->id, $payload);
    }
}