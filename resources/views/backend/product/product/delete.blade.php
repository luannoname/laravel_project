
@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['delete']['title']])
@include('backend.dashboard.component.formError')
<form action="{{ route('product.destroy', $product->id) }}" method="POST" class="box">
    @include('backend.dashboard.component.destroy', ['model' => $product])
</form>

