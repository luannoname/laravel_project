@php
    $perpage = request('perpage') ?: old('perpage');
@endphp
<div class="perpage">
    <div class="uk-flex uk-flex-middle uk-flex-space-between">
        <select name="perpage" class="form-control input-control input-sm perpage filter mr-10">
            @for($i = 20; $i <= 200; $i+=20)
                <option {{ ($perpage == $i) ? 'selected' : '' }} value="{{ $i }}">{{ $i }} {{ __('messages.perpage') }}</option>
            @endfor
        </select>
    </div>
</div>