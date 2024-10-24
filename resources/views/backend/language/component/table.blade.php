<div class="table-responsive">
    <table class="table table-sm table-striped table-bordered">
        <thead>
        <tr>
            <th>
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th class="text-center" style="width:100px;">Ảnh</th>
            <th>Tên ngôn ngữ</th>
            <th class="text-center">Canonical</th>
            <th class="text-center">Mô tả</th>
            <th class="text-center">Tình Trạng</th>
            <th class="text-center">Thao Tác</th>
        </tr>
        </thead>
        <tbody>
            @if (isset($languages) && is_object($languages))
                @foreach($languages as $language)
                <tr>
                    <td>
                        <input type="checkbox" value="{{ $language->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td class="text-center">
                        <span><img class="image img-cover" src="{{ asset(env('APP_URL') . $language->image) }}" alt=""></span>
                    </td>
                    <td>{{ $language->name }}</td>
                    <td>{{ $language->canonical }}</td>
                    <td>{{ $language->description }}</td>
                    <td class="text-center js-switch-{{ $language->id }}">
                        <input type="checkbox" value="{{ $language->publish }}" 
                        class="js-switch status " 
                        data-field="publish" 
                        data-model="{{ $config['model'] }}"
                        data-modelId="{{ $language->id }}"
                        {{ ($language->publish == 1) ? 'checked' : '' }}/>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('language.edit', $language->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>

                        <a href="{{ route('language.delete', $language->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    {{ $languages->links('pagination::bootstrap-5') }}
    
</div>