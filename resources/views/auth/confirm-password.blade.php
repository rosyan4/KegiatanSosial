@extends('layouts.guest')

@section('title', 'Confirm Password')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Confirm Password</h1>
</div>

<!-- Information Text -->
<div class="row mb-3">
    <div class="col-12">
        <div class="alert alert-warning">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </div>
    </div>
</div>

<!-- Confirm Password Form -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               required 
                               autocomplete="current-password">
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Confirm') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection