<?php

namespace App\Services;

use App\Repositories\Interfaces\BaseRepositoryInterface as BaseRepository;
use App\Services\Interfaces\BaseServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Class BaseService
 * @package App\Services
 */
class BaseService implements BaseServiceInterface
{

    public function __construct(

    ) {

    }

    public function currentLanguage() {
        return 1;
    }
    
}
