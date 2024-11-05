<div class="ibox">
    <div class="ibox-title">
        <h5>{{ __('messages.parent') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12 mb-15">
                <div class="form-row">
                        <p class="text-danger notice">{{ __('messages.parent_notice') }}</p>
                    <select name="parent_id" class="form-control setupSelect2">
                        @foreach ($dropdown as $key => $val)
                            @if ($key != $postCatalogue->id)
                                <option {{$key == old('parent_id', (isset($postCatalogue->parent_id)) ? $postCatalogue->parent_id : '')
                                    ? 'selected' : ''}}
                                    value="{{ $key }}"  value="{{ $key }}">{{ $val }}
                                </option>
                            @endif
                        @endforeach
                        
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ibox">
    <div class="ibox-title">
        <h5>{{ __('messages.image') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12 mb-15">
                <div class="form-row">
                    <span class="image img-cover image-target">
                        <img width="225px" src="{{ old('image', ($postCatalogue->image) ?? 'backend/img/not-found-img.jpg') ?? 'backend/img/not-found-img.jpg' }}" alt="">
                    </span>
                    <input
                    type="hidden"
                    name="image"
                    value="{{ old('image', ($postCatalogue->image) ?? '') }}"
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
                                {{$key == old('publish', (isset($postCatalogue->publish)) ? $postCatalogue->publish : '')
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
                            {{$key == old('follow', (isset($postCatalogue->follow)) ? $postCatalogue->follow : '')
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