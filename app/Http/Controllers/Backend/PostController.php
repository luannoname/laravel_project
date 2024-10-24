<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Services\Interfaces\PostServiceInterface as PostService;
use App\Repositories\Interfaces\PostRepositoryInterface as PostRepository;
use Illuminate\Http\Request;
use App\Classes\Nestedsetbie;

class PostController extends Controller
{
    protected $postService;
    protected $postRepository;
    protected $nestedset;
    protected $language;

    public function __construct(
        PostService $postService, 
        PostRepository $postRepository, 
    ) {
        $this->postService = $postService;
        $this->postRepository = $postRepository;
        $this->nestedset = new Nestedsetbie([
            'table' => 'post_catalogues',
            'foreignkey' => 'post_catalogue_id',
            'language_id' => 1,
        ]);
        $this->language = $this->currentLanguage();
    }

    public function index(Request $request) {
        $posts = $this->postService->paginate($request);
        
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ],
            'model' => 'Post',
        ];
        $config['seo'] = config('apps.post');
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.post.post.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'posts',
            'dropdown',
        ));
    }

    public function create() {
        $config = $this->configData();
        $template = 'backend.post.post.store';
        $config['seo'] = config('apps.post');
        $config['method'] = 'create';
        $dropdown = $this->nestedset->Dropdown();
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown',
        ));
    }

    public function store(StorePostRequest $request) {
        if ($this->postService->create($request)) {
            return redirect()->route('post.index')->with('success', 'Thêm mới bản ghi thành công.');
        }
        return redirect()->route('postCatalogue.index')->with('error', 'Thêm mới bản ghi thất bại. Hãy thử lại.');
    }

    public function edit($id) {
        $post = $this->postRepository->getPostById($id, $this->language);
        $config = $this->configData();
        $config['seo'] = config('apps.post');
        $config['method'] = 'update';
        $dropdown = $this->nestedset->Dropdown();
        $album = json_decode($post->album);
        $template = 'backend.post.post.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'post',
            'config',
            'dropdown',
            'album',
        ));
    }

    public function update($id, UpdatePostRequest $request) {
        if ($this->postService->update($id, $request)) {
            return redirect()->route('post.index')->with('success', 'Cập nhật bản ghi thành công.');
        }
        return redirect()->route('post.index')->with('error', 'Cập nhật bản ghi thất bại. Hãy thử lại.');
    }

    public function delete($id) {
        $post = $this->postRepository->getPostById($id, $this->language);
        
        $config['seo'] = config('apps.post');
        $template = 'backend.post.post.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'post',
        ));
    }

    public function destroy($id) {
        if ($this->postService->destroy($id)) {
            return redirect()->route('post.index')->with('success', 'Xóa bản ghi thành công.');
        }
        return redirect()->route('post.index')->with('error', 'Xóa bản ghi thất bại. Hãy thử lại.');
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