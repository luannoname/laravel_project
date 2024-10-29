<div class="ibox">
    <div class="ibox-title">
        <h5>Chọn danh mục cha</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12 mb-15">
                <div class="form-row">
                        <p class="text-danger notice">*Tự động chọn Root nếu không có danh mục cha</p>
                    <select name="post_catalogue_id" class="form-control setupSelect2">
                        @foreach ($dropdown as $key => $val)
                            <option {{$key == old('post_catalogue_id', (isset($post->post_catalogue_id)) ? $post->post_catalogue_id : '')
                                ? 'selected' : ''}}
                                value="{{ $key }}"  value="{{ $key }}">{{ $val }}
                            </option>
                        @endforeach
                        
                    </select>
                </div>
            </div>
        </div>
        @php
            $catalogue = [];
            if (isset($post)) {
                foreach ($post->post_catalogues as $key => $val) {
                    $catalogue[] = $val->id;
                }
            }
        @endphp
        <div class="row">
            <div class="col-lg-12 mb-15">
                <div class="form-row">
                    <div>
                        <label class="form-label">Danh mục phụ</label>
                    </div>
                    <select multiple name="catalogue[]" class="form-control setupSelect2">
                        @foreach($dropdown as $key => $val)
                        <option 
                            @if(is_array(old('catalogue', (
                                isset($catalogue) && count($catalogue)) ?   $catalogue : [])
                                ) && isset($post->post_catalogue_id) && $key !== $post->post_catalogue_id &&  in_array($key, old('catalogue', (isset($catalogue)) ? $catalogue : []))
                            )
                            selected
                            @endif value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ibox">
    <div class="ibox-title">
        <h5>Chọn ảnh đại diện</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12 mb-15">
                <div class="form-row">
                    <span class="image img-cover image-target">
                        <img width="225px" src="{{ old('image', ($post->image) ?? 'backend/img/not-found-img.jpg') ?? 'backend/img/not-found-img.jpg' }}" alt="">
                    </span>
                    <input
                    type="hidden"
                    name="image"
                    value="{{ old('image', ($post->image) ?? '') }}"
                    >
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ibox">
    <div class="ibox-title">
        <h5>Cấu hình nâng cao</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12 mb-15">
                <div class="form-row">
                    <div class="mb-15">
                        <select name="publish" class="form-control setupSelect2">
                        @foreach (__('messages.publish') as $key => $val)
                            <option 
                                {{$key == old('publish', (isset($post->publish)) ? $post->publish : '')
                                ? 'selected' : ''}}
                                value="{{ $key }}" >
                                {{ $val }}
                            </option>
                        @endforeach
                    </select>
                    </div>
                    <div class="mb-15">
                        <select name="follow" class="form-control setupSelect2">
                        @foreach (__('messages.follow') as $key => $val)
                        <option 
                            {{$key == old('follow', (isset($post->follow)) ? $post->follow : '')
                            ? 'selected' : ''}}
                            value="{{ $key }}" >
                            {{ $val }}
                        </option>
                        @endforeach
                    </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>