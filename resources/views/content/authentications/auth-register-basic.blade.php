@php
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/blankLayout')

@section('title', 'Register')

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <div class="card">
        <div class="card-body">
          <div class="app-brand justify-content-center">
            <a href="{{ url('/') }}" class="app-brand-link gap-2">
              <span class="app-brand-logo demo">
                <img src="{{ asset('assets/img/branding/logo.png') }}" alt="{{ config('app.name') }}" style="height: 48px;">
              </span>
            </a>
          </div>

          <h4 class="mb-1">Create your account</h4>
          <p class="mb-6">Register to access the platform.</p>

          <form method="POST" action="{{ route('register') }}" class="mb-6">
            @csrf

            @if ($errors->any())
              <div class="alert alert-danger">
                <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <div class="mb-3">
              <label for="first_name" class="form-label">First Name</label>
              <input
                type="text"
                class="form-control"
                id="first_name"
                name="first_name"
                value="{{ old('first_name') }}"
                placeholder="Enter your first name"
                autofocus
              >
            </div>

            <div class="mb-3">
              <label for="last_name" class="form-label">Last Name</label>
              <input
                type="text"
                class="form-control"
                id="last_name"
                name="last_name"
                value="{{ old('last_name') }}"
                placeholder="Enter your last name"
                autofocus
              >
            </div>

            <div class="mb-6">
              <label for="email" class="form-label">Email</label>
              <input
                type="email"
                class="form-control"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Enter your email"
              >
            </div>

            <div class="mb-6">
              <label for="password" class="form-label">Password</label>
              <input
                type="password"
                class="form-control"
                id="password"
                name="password"
                placeholder="••••••••"
              >
            </div>

            <div class="mb-6">
              <label for="password_confirmation" class="form-label">Confirm Password</label>
              <input
                type="password"
                class="form-control"
                id="password_confirmation"
                name="password_confirmation"
                placeholder="••••••••"
              >
            </div>

            <button type="submit" class="btn btn-primary d-grid w-100">
              Create Account
            </button>
          </form>

          <p class="text-center">
            <span>Already have an account?</span>
            <a href="{{ route('login') }}">
              <span>Sign in instead</span>
            </a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection