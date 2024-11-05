<?php

namespace App\Repositories;

use App\Models\Generate;
use App\Repositories\Interfaces\GenerateRepositoryInterface;

/**
 * Class GenerateRepository
 * @package App\Repositories
 */
class GenerateRepository extends BaseRepository implements GenerateRepositoryInterface
{
    protected $model;

    public function __construct(Generate $model) {
        $this->model = $model;
    }
    
}
