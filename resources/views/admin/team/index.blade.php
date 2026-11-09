@extends('layouts.admin')

@section('title', __('app.admin.team'))

@section('content')
<div class="container">
    <h1 class="mb-2">{{ __('app.admin.team') }}</h1>
    <p class="text-muted">{{ __('app.admin.team_help') }}</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('temp_password'))
        <div class="alert alert-warning">{{ __('app.admin.team_reset') }} : <code>{{ session('temp_password') }}</code></div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <h2 class="h5">{{ __('app.admin.team_add') }}</h2>
            <form method="POST" action="{{ route('admin.team.store') }}">
                @csrf
                <div class="row g-2">
                    <div class="col-md-4">
                        <label class="form-label" for="name">{{ __('app.admin.team_name') }}</label>
                        <input class="form-control" id="name" name="name" value="{{ old('name') }}" required maxlength="255">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="email">{{ __('app.auth.email') }}</label>
                        <input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" required maxlength="255">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="password">{{ __('app.admin.team_password') }}</label>
                        <input class="form-control" id="password" type="password" name="password" required minlength="8" autocomplete="new-password">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="password_confirmation">{{ __('app.auth.password_confirm') }}</label>
                        <input class="form-control" id="password_confirmation" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password">
                    </div>
                </div>
                @if($errors->any())
                    <div class="alert alert-danger mt-3 mb-0">{{ $errors->first() }}</div>
                @endif
                <button class="btn btn-success mt-3" type="submit">{{ __('app.admin.team_add') }}</button>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>{{ __('app.admin.team_name') }}</th>
                    <th>{{ __('app.auth.email') }}</th>
                    <th>{{ __('app.common.status') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $member)
                    <tr>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->email }}</td>
                        <td>{{ $member->isEditor() ? __('app.admin.team_editor') : __('app.admin.team_owner') }}</td>
                        <td class="text-end">
                            @if($member->isEditor())
                                <form method="POST" action="{{ route('admin.team.reset', $member) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-secondary" type="submit">{{ __('app.admin.team_reset') }}</button>
                                </form>
                                <form method="POST" action="{{ route('admin.team.destroy', $member) }}" class="d-inline" onsubmit="return confirm('{{ __('app.admin.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">{{ __('app.common.delete') }}</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
