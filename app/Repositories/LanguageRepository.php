<?php

namespace App\Repositories;

use App\Models\Language;
use App\Repositories\Interfaces\LanguageRepositoryInterface;
use Faker\Provider\Base;

/**
 * Class LanguageRepository
 * @package App\Repositories
 */
class LanguageRepository extends BaseRepository implements LanguageRepositoryInterface
{
    protected $model;

    public function __construct(Language $model) {
        $this->model = $model;
    }


}
