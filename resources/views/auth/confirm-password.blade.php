<!-- resources/views/auth/confirm-password.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto mt-12 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-teal-500 mb-6">Confirm Your Password</h2>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" required />
            </div>

            <button type="submit" class="bg-teal-500 text-white px-6 py-2 rounded-lg hover:bg-teal-600 w-full">
                Confirm Password
            </button>
        </form>
    </div>
@endsection
