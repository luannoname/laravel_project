<div class="ibox">
    <div class="ibox-title">
        <h5>{{ __('messages.image') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12 mb-15">
                <div class="form-row">
                    <span class="image img-cover image-target">
                        <img width="225px" src="{{ old('image', ($model->image) ?? 'backend/img/not-found-img.jpg') ?? 'backend/img/not-found-img.jpg' }}" alt="">
                    </span>
                    <input
                    type="hidden"
                    name="image"
                    value="{{ old('image', ($model->image) ?? '') }}"
                    >
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ibox">
    <div class="ibox-title">
        <h5>{{ __('messages.advance') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12 mb-15">
                <div class="form-row">
                    <div class="mb-15">
                        <select name="publish" class="form-control setupSelect2">
                        @foreach (__('messages.publish') as $key => $val)
                            <option 
                                {{$key == old('publish', (isset($model->publish)) ? $model->publish : '')
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
                            {{$key == old('follow', (isset($model->follow)) ? $model->follow : '')
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