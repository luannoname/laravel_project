<div class="ibox">
    <div class="ibox-title">
        <h5>Chọn danh mục cha</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12 mb-15">
                <div class="form-row">
                        <p class="text-danger notice">{{ __('messages.parent_notice') }}</p>
                    <select name="product_catalogue_id" class="form-control setupSelect2">
                        @foreach ($dropdown as $key => $val)
                            <option {{$key == old('product_catalogue_id', (isset($product->product_catalogue_id)) ? $product->product_catalogue_id : '')
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
            if (isset($product)) {
                foreach ($product->product_catalogues as $key => $val) {
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
                                ) && isset($product->product_catalogue_id) && $key !== $product->product_catalogue_id &&  in_array($key, old('catalogue', (isset($catalogue)) ? $catalogue : []))
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
@include('backend.dashboard.component.publish', ['model' => ($product) ?? null])