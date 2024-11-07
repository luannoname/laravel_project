
@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['delete']['title']])
@include('backend.dashboard.component.formError')
<form action="{{ route('post.destroy', $post->id) }}" method="POST" class="box">
    @include('backend.dashboard.component.destroy', ['model' => $post])
</form>

