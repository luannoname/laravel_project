@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo'][$config['method']]['title']])
@include('backend.dashboard.component.formError')

<form action="{{ route('language.storeTranslate') }}" method="post">
    @csrf
    <input type="hidden" name="option[id]" value="{{ $option['id'] }}">
    <input type="hidden" name="option[languageId]" value="{{ $option['languageId'] }}">
    <input type="hidden" name="option[model]" value="{{ $option['model'] }}">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-6">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ __('messages.tableHeading') }}</h5>
                    </div>
                    <div class="ibox-content">
                        @include('backend.dashboard.component.content', ['model' => ($object) ?? null, 'disabled' => 1])
                    </div>
                </div>
                @include('backend.dashboard.component.seo', ['model' => ($object) ?? null, 'disabled' => 1])
            </div>
            <div class="col-lg-6">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ __('messages.tableHeading') }}</h5>
                    </div>
                    <div class="ibox-content">
                        @include('backend.dashboard.component.translate', ['model' => ($objectTranslate) ?? null])
                    </div>
                </div>
                @include('backend.dashboard.component.seoTranslate', ['model' => ($objectTranslate) ?? null])
            </div>
        </div>
        <hr>
        
        <div class="text-right mb-15 button-fix">
            <button type="submit" class="btn btn-primary" name="send" value="send">{{ __('messages.save') }}</button>
        </div>
    </div>
</form>