<!-- resources/views/auth/login.blade.php -->
@extends('layouts.guest')

@section('content')
    <div class="max-w-md mx-auto mt-12 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-teal-500 mb-6">Login to Your Account</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" value="{{ old('email') }}" required autofocus />
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" required />
                @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-between items-center">
                <button type="submit" class="bg-teal-500 text-white px-6 py-2 rounded-lg hover:bg-teal-600">
                    Login
                </button>
                <a href="{{ route('password.request') }}" class="text-teal-500 hover:text-teal-600 text-sm">Forgot Password?</a>
            </div>
        </form>
    </div>
@endsection
