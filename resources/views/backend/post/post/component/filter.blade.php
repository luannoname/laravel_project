<form action="{{ route('post.index') }}">
    <div class="filter-wraper">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            @include('backend.dashboard.component.perpage')
            <div class="action">
                <div class="uk-flex uk-flex-middle">
                    @include('backend.dashboard.component.filterPublish')
                    @php
                        $postCatalogueId = request('post_catalogue_id') ?: old('post_catalogue_id');
                    @endphp
                    <select name="post_catalogue_id" class="form-control mr-10 setupSelect2">
                        @foreach ($dropdown as $key => $val)
                            <option {{ ($postCatalogueId == $key) ? 'selected' : '' }} value="{{ $key }}">{{ $val }}</option>   
                        @endforeach
                    </select>
                    @include('backend.dashboard.component.keyword')
                    <a href="{{ route('post.create') }}" class="btn btn-danger"><i class="fa fa-plus mr-5"></i>{{ __('messages.post.create.title') }}</a>
                </div>
            </div>
        </div>
    </div>
    
</form>