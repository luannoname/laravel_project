<div class="table-responsive">
    <table class="table table-sm table-striped table-bordered">
        <thead>
        <tr>
            <th style="width:50px;">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th>{{ __('messages.tableName') }}</th>
            @include('backend.dashboard.component.languageTh')
            <th class="text-center" style="width:100px;">{{ __('messages.tableStatus') }}</th>
            <th class="text-center" style="width:100px;">{{ __('messages.tableAction') }}</th>
        </tr>
        </thead>
        <tbody>
            @if (isset($productCatalogues) && is_object($productCatalogues))
                @foreach($productCatalogues as $productCatalogue)
                <tr>
                    <td>
                        <input type="checkbox" value="{{ $productCatalogue->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td>
                        {!! str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', (($productCatalogue->level > 0)?
                        ($productCatalogue->level - 1):0)).$productCatalogue->name !!}
                    </td>
                    @include('backend.dashboard.component.languageTd', ['model' => $productCatalogue, 'modeling' => 'ProductCatalogue'])
                    <td class="text-center js-switch-{{ $productCatalogue->id }}">
                        <input type="checkbox" value="{{ $productCatalogue->publish }}" 
                        class="js-switch status " 
                        data-field="publish" 
                        data-model="{{ $config['model'] }}"
                        data-modelId="{{ $productCatalogue->id }}"
                        {{ ($productCatalogue->publish == 1) ? 'checked' : '' }}/>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('product.catalogue.edit', $productCatalogue->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>

                        <a href="{{ route('product.catalogue.delete', $productCatalogue->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    {{ $productCatalogues->links('pagination::bootstrap-5') }}
    
</div>