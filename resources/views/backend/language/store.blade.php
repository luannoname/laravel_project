
@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo'][$config['method']]['title']])
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@php
    $url = ($config['method'] == 'create') ? route('language.store') : route('language.update', $language->id);
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-heading">
                    <div class="panel-title">Thông tin chung</div>
                    <div class="panel-description">
                        <p>- Nhập thông tin chung của ngôn ngữ</p>
                        <p>- Lưu ý: Những trường đánh dấu <span class="text-danger">(*) </span>là bắt buộc</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-6 mb-15">
                                <div class="form-row">
                                    <label class="control-label text-left">Tên ngôn ngữ
                                        <span class="text-danger">(*)</span></label>
                                    <input
                                        type="text"
                                        name="name"
                                        value="{{ old('name', ($language->name) ?? '') }}"
                                        class="form-control"
                                        placeholder=""
                                        autocomplete="off"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-6 mb-15">
                                <div class="form-row">
                                    <label class="control-label text-left">Canonical
                                        <span class="text-danger">(*)</span></label>
                                    <input
                                        type="text"
                                        name="canonical"
                                        value="{{ old('canonical', ($language->canonical) ?? '') }}"
                                        class="form-control"
                                        placeholder=""
                                        autocomplete="off"
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 mb-15">
                                <div class="form-row">
                                    <label class="control-label text-left">Ảnh đại diện
                                        </label>
                                    <input
                                        type="text"
                                        name="image"
                                        value="{{ old('name', ($language->image) ?? '') }}"
                                        class="form-control upload-image"
                                        data-type="Images"
                                        placeholder=""
                                        autocomplete="off"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-6 mb-15">
                                <div class="form-row">
                                    <label class="control-label text-left">Ghi chú
                                        </label>
                                    <input
                                        type="text"
                                        name="description"
                                        value="{{ old('description', ($language->description) ?? '') }}"
                                        class="form-control"
                                        placeholder=""
                                        autocomplete="off"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        
        <div class="text-right mb-15">
            <button type="submit" class="btn btn-primary" name="send" value="send">Lưu thay đổi</button>
        </div>
    </div>
</form>
