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
                            @if (!isset($postCatalogue) || $key != $postCatalogue->id)
                                <option {{$key == old('parent_id', (isset($postCatalogue->parent_id)) ? $postCatalogue->parent_id : '') ? 'selected' : ''}}
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
@include('backend.dashboard.component.publish', ['model' => ($postCatalogue) ?? null])