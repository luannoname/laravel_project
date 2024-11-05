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
            $controller = $this->makeDatabase($request);

            // $this->makeController();
            // $this->makeModel();
            // $this->makeRepository();
            // $this->makeService();
            // $this->makeProvider();
            // $this->makeRequest();
            // $this->makeView();
            // $this->makeRoutes();
            // $this->makeRule();
            // $this->makeLang();

            $payload = $request->except(['_token', 'send']);
            $payload['user_id'] = Auth::id();
            $generate = $this->generateRepository->create($payload);
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

    public function saveTranslate($option, $request) {
        DB::beginTransaction();
        try {
            $payload = [
                'name' => $request->input('translate_name'),
                'description' => $request->input('translate_description'),
                'content' => $request->input('translate_content'),
                'meta_title' => $request->input('translate_meta_title'),
                'meta_keyword' => $request->input('translate_meta_keyword'),
                'meta_description' => $request->input('translate_meta_description'),
                'canonical' => $request->input('translate_canonical'),
                $this->converModelToField($option['model']) => $option['id'],
                'language_id' => $option['languageId'],
            ];

            $repositoryNamespace = '\App\Repositories\\' .ucfirst($option['model']) . 'Repository';
            if (class_exists($repositoryNamespace)) {
                $repositoryInstance = app($repositoryNamespace);
            }
            $model = $repositoryInstance->findById($option['id']);
            $model->languages()->detach([$option['languageId'], $model->id]);
            $repositoryInstance->createPivot($model, $payload, 'languages');

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
    }

    private function converModelToField($model) {
        $temp = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $model));
        return $temp.'_id';
    }

    private function paginateSelect() {
        return [
            'id',
            'name',
            'schema',
        ];
    }
    
}
