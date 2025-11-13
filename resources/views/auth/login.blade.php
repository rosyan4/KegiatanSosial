@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<!-- Session Status -->
@if (session('status'))
    <div class="alert alert-success mb-4">
        {{ session('status') }}
    </div>
@endif

<!-- Login Form -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Email') }}</label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               autocomplete="username">
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

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

                    <!-- Remember Me -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input id="remember_me" 
                                   type="checkbox" 
                                   class="form-check-input" 
                                   name="remember">
                            <label class="form-check-label" for="remember_me">
                                {{ __('Remember me') }}
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-decoration-none">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif

                        <button type="submit" class="btn btn-primary">
                            {{ __('Log in') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection