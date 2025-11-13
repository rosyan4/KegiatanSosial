@extends('layouts.guest')

@section('title', 'Verify Email')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Verify Email</h1>
</div>

<!-- Information Text -->
<div class="row mb-3">
    <div class="col-12">
        <div class="alert alert-info">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>
    </div>
</div>

<!-- Verification Link Sent Status -->
@if (session('status') == 'verification-link-sent')
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-success">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        </div>
    </div>
@endif

<!-- Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            {{ __('Resend Verification Email') }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-link text-decoration-none">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection