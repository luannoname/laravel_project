<?php

namespace App\Services;

use App\Repositories\Interfaces\GenerateRepositoryInterface as GenerateRepository;
use App\Services\Interfaces\GenerateServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

/**
 * Class GenerateService
 * @package App\Services
 */
class GenerateService implements GenerateServiceInterface
{
    protected $generateRepository;

    public function __construct(
        GenerateRepository $generateRepository,
    ) {
        $this->generateRepository = $generateRepository;
    }
    public function paginate($request) {
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish'),
        ];
        $perPage = $request->integer('perpage');
        $generates = $this->generateRepository->pagination(
            $this->paginateSelect(),
            $condition, 
            $perPage,
            ['path' => 'generate/index'],
        );
        return $generates;
    }

    public function create($request) {
        DB::beginTransaction();
        try {
            // $database = $this->makeDatabase($request);
            // $controller = $this->makeController($request);
            // $model = $this->makeModel($request);
            // $repositoty = $this->makeRepository($request);
            // $service = $this->makeService($request);
            // $provider = $this->makeProvider($request);
            // $makeRequest = $this->makeRequest($request);
            $view = $this->makeView($request);

            // $routes = $this->makeRoutes($request);
            // $rule = $this->makeRule($request);
            // $lang = $this->makeLang($request);

            // $payload = $request->except(['_token', 'send']);
            // $payload['user_id'] = Auth::id();
            // $generate = $this->generateRepository->create($payload);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();

            echo $e->getMessage();die();
            return false;
        }
    }

    private function makeDatabase($request) {
        DB::beginTransaction();
        try {
            //Tạo cơ sở dữ liệu
            //Tạo file migration
            $payload = $request->only('schema', 'name', 'module_type');
            $tableName = $this->converModuleNameToTableName($payload['name']).'s';
            $migrationFileName = date('Y_m_d_His').'_create_'.$tableName.'_table.php';
            $migrationPath = database_path('migrations/'.$migrationFileName);
            $migrationTemplate = $this->createMigrationFile($payload);
            FILE::put($migrationPath, $migrationTemplate);
            if ($payload['module_type'] !== 3) {
                $foreignKey = $this->converModuleNameToTableName($payload['name']).'_id';
                $pivotTableName = $this->converModuleNameToTableName($payload['name']).'_language';
                $pivotSchema = $this->pivotSchema($tableName, $foreignKey, $pivotTableName);
                $migrationPivotTemplate = $this->createMigrationFile([
                    'schema' => $pivotSchema,
                    'name' => $pivotTableName,
                ]);
                $migrationPivotFileName = date('Y_m_d_His', time() + 10).'_create_'.$pivotTableName.'_table.php';
                $migrationPivotPath = database_path('migrations/'.$migrationPivotFileName);
                FILE::put($migrationPivotPath, $migrationPivotTemplate);
            }
            
            ARTISAN::call('migrate');
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
        
    }

    private function pivotSchema($tableName = '', $foreignKey = '', $pivot = '') {
        $pivotSchema = <<<SCHEMA
Schema::create('{$pivot}', function (Blueprint \$table) {
    \$table->unsignedBigInteger('{$foreignKey}');
    \$table->unsignedBigInteger('language_id');
    \$table->foreign('{$foreignKey}')->references('id')->on('{$tableName}')->onDelete('cascade');
    \$table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
    \$table->string('name');
    \$table->string('canonical')->unique();
    \$table->text('description');
    \$table->longText('content');
    \$table->string('meta_title');
    \$table->string('meta_keyword');
    \$table->text('meta_description');
    \$table->timestamps();
});
SCHEMA;
        return $pivotSchema;
    }

    private function createMigrationFile($payload) {
        $migrationTemplate = <<<MIGRATION
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        {$payload['schema']}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('{$this->converModuleNameToTableName
        ($payload['name'])}');
    }
};
MIGRATION;
        return $migrationTemplate;
    }

    private function converModuleNameToTableName($name) {
        $temp = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
        return $temp;
    }

    private function makeController($request) {
        $payload = $request->only('name', 'module_type');
        switch ($payload['module_type']) {
            case 1:
                $this->createTemplateController($payload['name'], 'TemplateCatalogueController');
                break;

            case 2:
                $this->createTemplateController($payload['name'], 'TemplateController');
                break;
            
            default:
                $this->createSingleController();
        }
    }

    private function createTemplateController($name, $controllerFile) {
        try {
            $controllerName = $name.'Controller.php';
            $templateControllerPath = base_path('app/Templates/'.$controllerFile.'.php');
            $controllerContent = file_get_contents($templateControllerPath);
            $replace = [
                'ModuleTemplate' => $name,
                'moduleTemplate' => lcfirst($name),
                'foreignKey' => $this->converModuleNameToTableName($name).'_id',
                'tableName' => $this->converModuleNameToTableName($name).'s',
                'moduleView' => str_replace('_', '.', $this->converModuleNameToTableName($name)),
            ];

            foreach ($replace as $key => $val) {
                $controllerContent = str_replace('{'.$key.'}', $replace[$key], $controllerContent);
            }
            
            $controllerPath = base_path('app/Http/Controllers/Backend/'.$controllerName);
            FILE::put($controllerPath, $controllerContent);
            die();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();

            echo $e->getMessage();die();
            return false;
        }
    }

    private function makeModel($request) {
        try {
            if ($request->input('module_type') == 1) {
                $this->createModelTemplate($request);
            } else {
                dd(1);
            }

            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();die();
            return false;
        }
    }

    private function createModelTemplate($request) {
        $modelName = $request->input('name').'.php';
        $templateModelPath = base_path('app/Templates/TemplateCatalogueModel.php');
        $modelContent = file_get_contents($templateModelPath);
        $module = $this->converModuleNameToTableName($request->input('name'));
        $extractModule = explode('_', $module);
        $replace = [
            'ModuleTemplate' => $request->input('name'),
            'foreignKey' => $module.'_id',
            'tableName' => $module.'s',
            'relation' => $extractModule[0],
            'pivotModel' => $request->input('name').'Language',
            'relationPivot' => $module.'_'.$extractModule[0],
            'pivotTable' => $module.'_language',
            'module' => $module,
            'relationModel' => ucfirst($extractModule[0]),
        ];

        foreach ($replace as $key => $val) {
            $modelContent = str_replace('{'.$key.'}', $replace[$key], $modelContent);
        }
        
        $modelPath = base_path('app/Models/'.$modelName);
        FILE::put($modelPath, $modelContent);
    }

    private function makeRepository($request) {
        try {
            $name = $request->input('name');
            $module = $this->converModuleNameToTableName($name);
            $moduleExtract = explode('_', $module);

            $repository = $this->initializeServiceLayer('Repository', 'Repositories', $request);
            $replace = [
                'Module' => $name,
            ];

            $repositoryInterfaceContent = $repository['interface']['layerInterfaceContent'];
            $repositoryInterfaceContent = str_replace('{Module}', $replace['Module'], $repositoryInterfaceContent);

            $replaceRepository = [
                'Module' => $name,
                'tableName' => $module.'s',
                'pivotTableName' => $module.'_'.$moduleExtract[0],
                'foreignKey' => $module.'_id',
            ];
            $repositoryContent = $repository['service']['layerContent'];
            foreach ($replaceRepository as $key => $val) {
                $repositoryContent = str_replace('{'.$key.'}', $replaceRepository[$key], $repositoryContent);
            }
            FILE::put($repository['interface']['layerInterfacePath'], $repositoryInterfaceContent);
            FILE::put($repository['service']['layerPathPut'], $repositoryContent);
            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();die();
            return false;
        }
    }

    private function makeService($request) {
        try {
            $name = $request->input('name');
            $module = $this->converModuleNameToTableName($name);
            $moduleExtract = explode('_', $module);

            $service = $this->initializeServiceLayer('Service', 'Services', $request);
           
            $replace = [
                'Module' => $name,
            ];

            $serviceInterfaceContent = $service['interface']['layerInterfaceContent'];
            $serviceInterfaceContent = str_replace('{Module}', $replace['Module'], $serviceInterfaceContent);

            $replaceService = [
                'Module' => $name,
                'module' => lcfirst($name),
                'moduleView' => str_replace('_', '.', $this->converModuleNameToTableName($name)),
                'tableName' => $module.'s',
                'foreignKey' => $module.'_id',
            ];
            $serviceContent = $service['service']['layerContent'];
            foreach ($replaceService as $key => $val) {
                $serviceContent = str_replace('{'.$key.'}', $replaceService[$key], $serviceContent);
            }
            FILE::put($service['interface']['layerInterfacePath'], $serviceInterfaceContent);
            FILE::put($service['service']['layerPathPut'], $serviceContent);
            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();die();
            return false;
        }
    }

    private function initializeServiceLayer($layer = '', $folder = '', $request) {
        $name = $request->input('name');
        
        $option = [
            $layer.'Name' => $name.$layer,
            $layer.'Interface' => $name.$layer.'Interface',
        ];
        $layerInterfaceRead = base_path('app/Templates/Template'.$layer.'Interface.php' );
        $layerInterfaceContent = file_get_contents($layerInterfaceRead);
        $layerInterfacePath = base_path('app/'.$folder.'/Interfaces/'.$option[$layer.'Interface'].'.php');
        
        $layerPathRead = base_path('app/Templates/Template'.$layer.'.php' );
        $layerContent = file_get_contents($layerPathRead);
        $layerPathPut = base_path('app/'.$folder.'/'.$option[$layer.'Name'].'.php');

        return [
            'interface' => [
                'layerInterfaceContent' => $layerInterfaceContent,
                'layerInterfacePath' => $layerInterfacePath,
            ],
            'service' => [
                'layerContent' => $layerContent,
                'layerPathPut' => $layerPathPut,
            ],
        ];
    }

    private function makeProvider($request) {
        try {
            $name = $request->input('name');
            $provider = [
                'providerPath' => base_path('app/Providers/AppServiceProvider.php'),
                'repositoryProviderPath' => base_path('app/Providers/RepositoryServiceProvider.php'),
            ];

            foreach ($provider as $key => $val) {
                $content = file_get_contents($val);
                $insertLine = ($key == 0) ? "'App\\Services\\Interfaces\\{$name}ServiceInterface' => 'App\\Services\\{$name}Service'," : "'AppRepositoriesInterfaces\\{$name}RepositoryInterface' => 'App\\Repositories\\{$name}Repository',";

                $position = strpos($content, '];');
                if ($position !== false) {
                    $newContent = substr_replace($content,"    ". $insertLine."\n"."    ", $position, 0);
                }
                File::put($val, $newContent);
            }
            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();die();
            return false;
        }
    }
    
    private function makeRequest($request) {
        try {
            // StoreModuleRequest, UpdateModuleRequest, DeleteModuleRequest
            $name = $request->input('name');
            $requestArray = [
                'Store'.$name.'Request', 
                'Update'.$name.'Request', 
                'Delete'.$name.'Request',
            ];
            $requestTemplate = [
                'RequestTemplateStore',
                'RequestTemplateUpdate',
                'RequestTemplateDelete',
            ];
            
            if ($request->input('module_type') != 1) {
                unset($requestArray[2]);
                unset($requestTemplate[2]);
            } 
            foreach ($requestTemplate as $key => $val) {
                $requestPath = base_path('app/Templates/'.$val.'.php');
                $requestContent = file_get_contents($requestPath);
                $requestContent = str_replace('{Module}', $name, $requestContent);
                $requestPut = base_path('app/Http/Requests/'.$requestArray[$key].'.php');
                FILE::put($requestPut, $requestContent);
            }
            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();die();
            return false;
        }
    }

    private function makeView($request) {
        try {
            $name = $request->input('name');
            $module = $this->converModuleNameToTableName($name);
            $extractModule = explode('_', $module);
            $basePath = resource_path("views/backend/{$extractModule[0]}");
            $folderPath = (count($extractModule) == 2) ? "$basePath/{$extractModule[1]}" : "$basePath/{$extractModule[0]}";
            $componentPath = "$folderPath/component";

            $this->createDirectory($folderPath);
            $this->createDirectory($componentPath);
            
            $sourcePath = base_path('app/Templates/views/'.((count($extractModule) == 2) ? 'catalogue' : 'post').'/');
            $viewPath = (count($extractModule) == 2) ? "{$extractModule[0]}.{$extractModule[1]}" : $extractModule[0];
            $replacement = [
                'view' => $viewPath,
                'module' => lcfirst($name),
                'Module' => $name,
            ];

            $fileArray = ['store.blade.php', 'index.blade.php', 'delete.blade.php',];
            $componentFile = ['aside.blade.php', 'filter.blade.php', 'table.blade.php'];
            $this->copyAndReplaceContent($sourcePath, $folderPath, $fileArray, $replacement);
            $this->copyAndReplaceContent("{$sourcePath}component/", $componentPath, $componentFile, $replacement);
            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();die();
            return false;
        }
        
    }

    private function createDirectory($path) {
        if (!FILE::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }

    private function copyAndReplaceContent(string $sourcePath, string $destinationPath, array $fileArray, array $replacement) {
        foreach ($fileArray as $key => $val) {
            $sourceFile = $sourcePath.$val;
            $destination = "{$destinationPath}/{$val}";
            $content = file_get_contents($sourceFile);
            foreach ($replacement as $keyReplace => $replace) {
                $content = str_replace('{'.$keyReplace.'}', $replace, $content);
            }
            if (!FILE::exists($destination)) {
                FILE::put($destination, $content);
            }
        }
    }

    public function update($id, $request) {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send']);
            $generate = $this->generateRepository->update($id, $payload);
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
            $generate = $this->generateRepository->delete($id);
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
            'id',
            'name',
            'schema',
        ];
    }
    
}
