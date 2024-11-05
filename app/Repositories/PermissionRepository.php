<?php

namespace App\Repositories;

use App\Models\Permission;
use App\Repositories\Interfaces\PermissionRepositoryInterface;
use Faker\Provider\Base;

/**
 * Class PermissionRepository
 * @package App\Repositories
 */
class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    protected $model;

    public function __construct(Permission $model) {
        $this->model = $model;
    }


}
