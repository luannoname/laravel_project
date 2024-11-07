<?php

namespace App\Services;

use App\Classes\Nestedsetbie;
use App\Repositories\Interfaces\{Module}RepositoryInterface as {Module}Repository;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use App\Services\BaseService;
use App\Services\Interfaces\{Module}ServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class {Module}Service
 * @package App\Services
 */
class {Module}Service extends BaseService implements {Module}ServiceInterface
{
    protected ${module}Repository;
    protected $routerRepository;
    protected $nestedset;
    protected $language;
    protected $controllerName = '{Module}Controller';

    public function __construct(
        {Module}Repository ${module}Repository,
        RouterRepository $routerRepository,
    ) {
        $this->{module}Repository = ${module}Repository;
        $this->routerRepository = $routerRepository;
        // $this->nestedset = new Nestedsetbie([
        //     'table' => '{tableName}',
        //     'foreignkey' => '{foreignKey}',
        //     'language_id' => $this->language,
        // ]);
    }
    public function paginate($request, $languageId) {
        $perPage = $request->integer('perpage');
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish'),
            'where' => [
                ['tb2.language_id', '=', $languageId]
            ],
        ];
        $paginationConfig = ['path' => '{moduleView}.index'];
        $joins = [
            ['post_catalogue_language as tb2', 'tb2.{foreignKey}', '=', '{tableName}.id'],
        ];
        $orderBy = ['{tableName}.lft', 'asc'];
        
        ${module}s = $this->{module}Repository->pagination(
            $this->paginateSelect(), 
            $condition, 
            $perPage,
            $paginationConfig,
            $orderBy,
            $joins,
        );
        return ${module}s;
    }

    public function create($request, $languageId) {
        DB::beginTransaction();
        try {
            ${module} = $this->createCatalogue($request);
            if (${module}->id > 0) {
                $this->updateLanguageForCatalogue(${module}, $request, $languageId);
                $this->createRouter(${module}, $request, $this->controllerName);
                $this->nestedset = new Nestedsetbie([
                    'table' => '{tableName}',
                    'foreignkey' => '{foreignKey}',
                    'language_id' => $languageId,
                ]);
                $this->nestedset();
            }
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();

            echo $e->getMessage();die();
            return false;
        }
    }

    public function update($id, $request, $languageId) {
        DB::beginTransaction();
        try {
            ${module} = $this->{module}Repository->findById($id);
            $flag = $this->updateCatalogue(${module}, $request);
            if ($flag == true) {
                $this->updateLanguageForCatalogue(${module}, $request, $languageId);
                $this->updateRouter(${module}, $request, $this->controllerName);
                $this->nestedset = new Nestedsetbie([
                    'table' => '{tableName}',
                    'foreignkey' => '{foreignKey}',
                    'language_id' => $languageId,
                ]);
                $this->nestedset();
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();

            echo $e->getMessage();die();
            return false;
        }
    }

    public function destroy($id) {
        DB::beginTransaction();
        try {
            ${module} = $this->{module}Repository->delete($id);
            $this->routerRepository->deleteByCondition([
                ['module_id', '=', $id],
                ['controllers', '=', 'App\Http\Controllers\Frontend\{Module}Controller'],
            ]);
            
            $this->nestedset = new Nestedsetbie([
                'table' => '{tableName}',
                'foreignkey' => '{foreignKey}',
                'language_id' =>  $languageId ,
            ]);
            $this->nestedset();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();

            echo $e->getMessage();die();
            return false;
        }
    }

    private function createCatalogue($request) {
        $payload = $request->only($this->payload());
        $payload['user_id'] = Auth::id();
        $payload['album'] = $this->formatAlbum($request);
        ${module} = $this->{module}Repository->create($payload);
        return ${module};
    }

    private function updateCatalogue(${module}, $request) {
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($request);
        $flag = $this->{module}Repository->update(${module}->id, $payload);
        return $flag;
    }

    private function updateLanguageForCatalogue(${module}, $request, $languageId) {
        $payload = $this->formatLanguagePayload($request, $languageId);
        ${module}->languages()->detach([$languageId]);
        $language = $this->{module}Repository->createPivot(${module}, $payload, 'languages');
        return $language;
    }

    private function formatLanguagePayload($request, $languageId) {
        $payload = $request->only($this->payloadLanguage());
        $payload['canonical'] = Str::slug($payload['canonical']);
        $payload['language_id'] = $languageId;
        // $payload['{foreignKey}'] = ${module}->id;
        return $payload;
    }

    public function updateStatus($post = []) {
        DB::beginTransaction();
        try {
            $payload[$post['field']] = (($post['value'] == 1)?2:1);
            ${module} = $this->{module}Repository->update($post['modelId'], $payload);
            // $this->changeUserStatus($post, $payload[$post['field']]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();

            echo $e->getMessage();die();
            return false;
        }
    }

    public function updateStatusAll($post) {
        DB::beginTransaction();
        try {
            $payload[$post['field']] = $post['value'];
            $flag = $this->{module}Repository->updateByWhereIn('id', $post['id'], $payload);
            // $this->changeUserStatus($post, $payload[$post['field']]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();

            echo $e->getMessage();die();
            return false;
        }
    }

    private function paginateSelect() {
        return [
            '{tableName}.id',
            '{tableName}.image',
            '{tableName}.publish',
            '{tableName}.level',
            '{tableName}.lft',
            '{tableName}.order',
            'tb2.name',
            'tb2.canonical',
        ];
    }

    private function payload() {
        return [
            'parent_id', 
            'follow', 
            'publish', 
            'image',
            'album',
        ];
    }

    private function payloadLanguage() {
        return  [
            'name', 
            'description', 
            'content', 
            'meta_title', 
            'meta_keyword', 
            'meta_description',
            'canonical',
        ];
        
    }

    
    
}
