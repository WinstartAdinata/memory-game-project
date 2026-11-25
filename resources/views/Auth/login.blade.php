@extends('welcome')

@section('content')
    @include('layouts.header')

    <!-- Login Form -->
    <div class="bg-white/80 backdrop-blur-md rounded-3xl shadow-xl p-7">

        <h2 class="text-2xl font-bold text-slate-800 mb-2">
            Welcome Back!
        </h2>

        <p class="text-sm text-slate-500 mb-6">
            Sign in to continue your adventure
        </p>

        @if ($errors->any())
            <div
                class="bg-rose-50 border border-rose-200 text-rose-600
                    rounded-xl p-3 mb-5 text-sm text-left">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('status'))
            <div
                class="bg-emerald-50 border border-emerald-200 text-emerald-700
                    rounded-xl p-3 mb-5 text-sm text-left">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('login.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Email -->
            <div class="text-left">
                <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                    Email Address
                </label>

                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    placeholder="Enter your email" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200
                        focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <!-- Password -->
            <div class="text-left">
                <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                    Password
                </label>

                <input type="password" id="password" name="password" placeholder="Enter your password" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200
                        focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <!-- Login Button -->
            <button type="submit"
                class="w-full py-3 bg-blue-600 hover:bg-blue-700
                    text-white font-bold rounded-xl shadow-md transition cursor-pointer">
                Login
            </button>
        </form>

        <!-- Register Link -->
        <p class="text-sm text-slate-500 mt-6">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:text-blue-800">
                Register
            </a>
        </p>

    </div>

    @include('layouts.footer')
@endsection
