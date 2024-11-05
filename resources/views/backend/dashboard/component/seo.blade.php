<div class="ibox">
    <div class="ibox-title">
        <h5>{{ __('messages.seo') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="seo-container">
            <div class="meta-title">
                {{ (old('meta_title', ($model->meta_title) ?? '')) ??  __('messages.seoTitle') }}
            </div>
            <div class="canonical">
                {{ (old('canonical', ($model->canonical) ?? '')) ? config('app.url').old('canonical', ($model->canonical) ?? '').
                config('apps.general.suffix') : __('messages.seoCanonical') }}
            </div>
            <div class="meta-description">
                {{ (old('meta_description', ($model->meta_description) ?? '')) ?? __('messages.seoDescription') }}
            </div>
        </div>
        <div class="seo-wrapper">
            <div class="row mb-15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                            <label for="" class="control-label text-left">{{ __('messages.seoMetaTitle') }}</label>
                            <label class="count_meta-title">{{ __('messages.character') }}</label>
                        </div>
                        <input
                            type="text"
                            name="meta_title"
                            value="{{ old('meta_title', ($model->meta_title) ?? '') }}"
                            class="form-control"
                            placeholder=""
                            autocomplete="off"
                            {{ (isset($disabled)) ? 'disabled' : '' }}>
                    </div>
                </div>
            </div>
            <div class="row mb-15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <div>
                            <label for="" class="control-label text-left">{{ __('messages.seoKeyword') }}</label>
                        </div>
                        <input
                            type="text"
                            name="meta_keyword"
                            value="{{ old('meta_keyword', ($model->meta_keyword) ?? '') }}"
                            class="form-control"
                            placeholder=""
                            autocomplete="off"
                            {{ (isset($disabled)) ? 'disabled' : '' }}>
                    </div>
                </div>
            </div>
            <div class="row mb-15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                            <label for="" class="control-label text-left">{{ __('messages.seoMetaDescription') }}</label>
                            <label class="count_meta-description">0 ký tự</label>
                        </div>
                        <textarea
                            type="text"
                            name="meta_description"
                            class="form-control"
                            placeholder=""
                            autocomplete="off"
                            {{ (isset($disabled)) ? 'disabled' : '' }}>{{ old('meta_description', ($model->meta_description) ?? '') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="row mb-15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <div>
                            <label for="" class="control-label text-left">
                                {{ __('messages.canonical') }}
                                <span class="text-danger">*</span>
                            </label>
                        </div>
                        <div class="input-wrapper">
                            <input
                            type="text"
                            name="canonical"
                            value="{{ old('canonical', ($model->canonical) ?? '') }}"
                            class="form-control seo-canonical"
                            placeholder=""
                            autocomplete="off"
                            {{ (isset($disabled)) ? 'disabled' : '' }}>
                            <span class="baseUrl">{{ config('app.url') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>