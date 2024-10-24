<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Faker\Provider\Base;

/**
 * Class UserRepository
 * @package App\Repositories
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model) {
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
    ) {
        $query = $this->model->select($column)->where(function($query) use ($condition) {
            if (isset($condition['keyword']) && !empty($condition['keyword'])) {
                $query->where('name', 'LIKE', '%'.$condition['keyword'].'%')
                ->orWhere('email', 'LIKE', '%'.$condition['keyword'].'%')
                ->orWhere('phone', 'LIKE', '%'.$condition['keyword'].'%')
                ->orWhere('address', 'LIKE', '%'.$condition['keyword'].'%');
            }
            if (isset($condition['publish']) && $condition['publish'] == 1) {
                $query->where('publish', '=', '1');
            } elseif (isset($condition['publish']) && $condition['publish'] == 2) {
                $query->where('publish', '=', '2');
            }
            return $query;
        })->with('user_catalogues');
        if(!empty($join)) {
            $query->join(...$join);
        }
        return $query->paginate($perpage)
                    ->withQueryString()->withPath(env('APP_URL').$extend['path']);
    }

}
