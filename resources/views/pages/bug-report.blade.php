@extends(\App\Support\ThemeView::layout())

@section('title', __('app.public.bug_title'))

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <h1>{{ __('app.public.bug_title') }}</h1>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('bug-report.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">{{ __('app.public.bug_title_label') }}</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">{{ __('app.public.bug_description') }}</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required></textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">{{ __('app.public.your_name') }}</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">{{ __('app.public.your_email') }}</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="captcha" class="form-label">{{ __('app.public.captcha') }}</label>
                            <input type="text" class="form-control @error('captcha') is-invalid @enderror" id="captcha" name="captcha" required>
                            @error('captcha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary">{{ __('app.public.send_report') }}</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('app.admin.bug_reports') }}</h5>
                </div>
                <div class="card-body">
                    @if($recentBugs->count() > 0)
                        <ul class="list-group">
                            @foreach($recentBugs as $bug)
                                <li class="list-group-item">
                                    <a href="{{ route('bug-report.show', $bug->id) }}" class="text-decoration-none">
                                        <h6 class="mb-1">{{ $bug->title }}</h6>
                                        <small class="text-muted">{{ \App\Support\Locale::formatDate($bug->created_at) }}</small>
                                        <span class="badge bg-{{ $bug->status == 'open' ? 'danger' : ($bug->status == 'in_progress' ? 'info' : 'success') }} float-end">
                                            {{ \App\Support\ThemeUi::statusLabel($bug->status) }}
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-center mb-0">{{ __('app.common.empty') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection