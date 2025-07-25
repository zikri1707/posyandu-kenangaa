<!-- resources/views/auth/verify-email.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto mt-12 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-teal-500 mb-6">Verify Your Email Address</h2>

        @if (session('resent'))
            <div class="mb-4 text-sm text-teal-500">
                A fresh verification link has been sent to your email address.
            </div>
        @endif

        <p class="text-sm text-gray-600">
            Before proceeding, please check your email for a verification link. If you did not receive the email,
            <form class="inline" method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="text-teal-500 hover:text-teal-600 text-sm">click here to request another</button>.
            </form>
        </p>
    </div>
@endsection
