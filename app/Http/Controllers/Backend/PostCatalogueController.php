<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostCatalogueRequest;
use App\Http\Requests\UpdatePostCatalogueRequest;
use App\Http\Requests\DeletePostCatalogueRequest;
use App\Services\Interfaces\PostCatalogueServiceInterface as PostCatalogueService;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface as PostCatalogueRepository;
use Illuminate\Http\Request;
use App\Classes\Nestedsetbie;

class PostCatalogueController extends Controller
{
    protected $postCatalogueService;
    protected $postCatalogueRepository;
    protected $nestedset;
    protected $language;

    public function __construct(
        PostCatalogueService $postCatalogueService, 
        PostCatalogueRepository $postCatalogueRepository, 
    ) {
        $this->postCatalogueService = $postCatalogueService;
        $this->postCatalogueRepository = $postCatalogueRepository;
        $this->nestedset = new Nestedsetbie([
            'table' => 'post_catalogues',
            'foreignkey' => 'post_catalogue_id',
            'language_id' => 1,
        ]);
        $this->language = $this->currentLanguage();
    }

    public function index(Request $request) {
        $postCatalogues = $this->postCatalogueService->paginate($request);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ],
            'model' => 'PostCatalogue',
        ];
        $config['seo'] = __('messages.postCatalogue');
        $template = 'backend.post.catalogue.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'postCatalogues',
        ));
    }

    public function create() {
        $config = $this->configData();
        $template = 'backend.post.catalogue.store';
        $config['seo'] = __('messages.postCatalogue');
        $config['method'] = 'create';
        $dropdown = $this->nestedset->Dropdown();
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown',
        ));
    }

    public function store(StorePostCatalogueRequest $request) {
        if ($this->postCatalogueService->create($request)) {
            return redirect()->route('post.catalogue.index')->with('success', 'Thêm mới bản ghi thành công.');
        }
        return redirect()->route('postCatalogue.index')->with('error', 'Thêm mới bản ghi thất bại. Hãy thử lại.');
    }

    public function edit($id) {
        $postCatalogue = $this->postCatalogueRepository->getPostCatalogueById($id, $this->language);

        $config = $this->configData();
        $config['seo'] = __('messages.postCatalogue');
        $config['method'] = 'update';
        $dropdown = $this->nestedset->Dropdown();
        $album = json_decode($postCatalogue->album);
        $template = 'backend.post.catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'postCatalogue',
            'config',
            'dropdown',
            'album',
        ));
    }

    public function update($id, UpdatePostCatalogueRequest $request) {
        if ($this->postCatalogueService->update($id, $request)) {
            return redirect()->route('post.catalogue.index')->with('success', 'Cập nhật bản ghi thành công.');
        }
        return redirect()->route('post.catalogue.index')->with('error', 'Cập nhật bản ghi thất bại. Hãy thử lại.');
    }

    public function delete($id) {
        $postCatalogue = $this->postCatalogueRepository->getPostCatalogueById($id, $this->language);
        
        $config['seo'] = __('messages.postCatalogue');
        $template = 'backend.post.catalogue.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'postCatalogue',
        ));
    }

    public function destroy($id, DeletePostCatalogueRequest $request) {
        if ($this->postCatalogueService->destroy($id)) {
            return redirect()->route('post.catalogue.index')->with('success', 'Xóa bản ghi thành công.');
        }
        return redirect()->route('post.catalogue.index')->with('error', 'Xóa bản ghi thất bại. Hãy thử lại.');
    }

    public function configData() {
        return [
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/seo.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ]
        ];
    }
}
