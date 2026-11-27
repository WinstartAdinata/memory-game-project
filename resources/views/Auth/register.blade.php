@extends('welcome')

@section('content')
    @include('layouts.header')

    <div class="bg-white/80 backdrop-blur-md rounded-3xl shadow-xl p-7">

        <h2 class="text-2xl font-bold text-slate-800 mb-2">
            Create Account
        </h2>

        <p class="text-sm text-slate-500 mb-6">
            Join us and start training your brain!
        </p>

        @if ($errors->any())
            <div
                class="bg-rose-50 border border-rose-200 text-rose-600
                    rounded-xl p-3 mb-5 text-sm text-left">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="text-left">
                <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">
                    Full Name
                </label>

                <input type="text" id="name" name="name" value="{{ old('name') }}"
                    placeholder="Enter your name" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200
                        focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="text-left">
                <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                    Email Address
                </label>

                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    placeholder="Enter your email" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200
                        focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="text-left">
                <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                    Password
                </label>

                <input type="password" id="password" name="password" placeholder="Minimum 8 characters" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200
                        focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="text-left">
                <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">
                    Confirm Password
                </label>

                <input type="password" id="password_confirmation" name="password_confirmation"
                    placeholder="Re-enter your password" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200
                        focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <button type="submit"
                class="w-full py-3 bg-blue-600 hover:bg-blue-700
                    text-white font-bold rounded-xl shadow-md transition cursor-pointer">
                Create Account
            </button>
        </form>

        <p class="text-sm text-slate-500 mt-6">
            Already have an account?
            <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:text-blue-800">
                Sign In
            </a>
        </p>

    </div>

    @include('layouts.footer')
@endsection
