@extends(\App\Support\ThemeView::layout())

@section('title', __('app.footer.privacy_long'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('app.footer.privacy_long') }}</div>
                <div class="card-body">
                    @if(isset($page) && $page)
                        {!! $page->content !!}
                    @else
                        <p>Politique de confidentialité du site.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection