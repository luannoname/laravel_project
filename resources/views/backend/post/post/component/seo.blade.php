<div class="ibox">
    <div class="ibox-title">
        <h5>Cấu hình SEO</h5>
    </div>
    <div class="ibox-content">
        <div class="seo-container">
            <div class="meta-title">
                {{ (old('meta_title', ($post->meta_title) ?? '')) ?? 'Bạn chưa có tiêu đề SEO' }}
            </div>
            <div class="canonical">
                {{ (old('canonical', ($post->canonical) ?? '')) ? config('app.url').old('canonical', ($post->canonical) ?? '').
                config('apps.general.suffix') : 'http://duong-dan-cua-ban.html' }}
            </div>
            <div class="meta-description">
                {{ (old('meta_description', ($post->meta_description) ?? '')) ?? 'Bạn chưa có mô tả SEO' }}
            </div>
        </div>
        <div class="seo-wrapper">
            <div class="row mb-15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                            <label for="" class="control-label text-left">Tiêu đề SEO</label>
                            <label class="count_meta-title">0 ký tự</label>
                        </div>
                        <input
                            type="text"
                            name="meta_title"
                            value="{{ old('meta_title', ($post->meta_title) ?? '') }}"
                            class="form-control"
                            placeholder=""
                            autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="row mb-15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <div>
                            <label for="" class="control-label text-left">Từ khóa SEO</label>
                        </div>
                        <input
                            type="text"
                            name="meta_keyword"
                            value="{{ old('meta_keyword', ($post->meta_keyword) ?? '') }}"
                            class="form-control"
                            placeholder=""
                            autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="row mb-15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                            <label for="" class="control-label text-left">Mô tả SEO</label>
                            <label class="count_meta-description">0 ký tự</label>
                        </div>
                        <textarea
                            type="text"
                            name="meta_description"
                            class="form-control"
                            placeholder=""
                            autocomplete="off">{{ old('meta_description', ($post->meta_description) ?? '') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="row mb-15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <div>
                            <label for="" class="control-label text-left">
                                Đường dẫn
                                <span class="text-danger">*</span>
                            </label>
                        </div>
                        <div class="input-wrapper">
                            <input
                            type="text"
                            name="canonical"
                            value="{{ old('canonical', ($post->canonical) ?? '') }}"
                            class="form-control"
                            placeholder=""
                            autocomplete="off">
                            <span class="baseUrl">{{ config('app.url') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>