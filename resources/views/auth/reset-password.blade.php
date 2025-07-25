<!-- resources/views/auth/reset-password.blade.php -->
@extends('layouts.guest')

@section('content')
    <div class="max-w-md mx-auto mt-12 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-teal-500 mb-6">Reset Password</h2>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" value="{{ old('email') }}" required />
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="bg-teal-500 text-white px-6 py-2 rounded-lg hover:bg-teal-600 w-full">
                Send Password Reset Link
            </button>
        </form>
    </div>
@endsection
