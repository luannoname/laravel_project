
@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['delete']['title']])
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('post.destroy', $post->id) }}" method="POST" class="box">
    @csrf
    @method('DELETE')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-heading">
                    <div class="panel-title">Thông tin chung</div>
                    <div class="panel-description">
                        <p>Bạn đang muốn xóa nhóm bài viết có tên là: <span class="text-danger">{{ $post->name }}</span></p>
                        <p>Lưu ý: Không thể khôi phục thành viên sau khi xóa. Hãy chắc chắn bạn muốn thực hiện chức năng này.<span class="text-danger">(*) </span>là bắt buộc</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-12 mb-15">
                                <div class="form-row">
                                    <label class="control-label text-left">Tên nhóm
                                        <span class="text-danger">(*)</span></label>
                                    <input
                                        type="text"
                                        name="name"
                                        value="{{ old('name', ($post->name) ?? '') }}"
                                        class="form-control"
                                        placeholder=""
                                        autocomplete="off"
                                        readonly
                                    >
                                    
                                </div>
                            </div>
                        </div>
                        
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mb-15">
            <button type="submit" class="btn btn-danger" name="send" value="send">Xóa dữ liệu</button>
        </div>
    </div>
</form>

