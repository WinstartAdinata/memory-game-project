@extends('welcome')

@section('content')
    @include('layouts.header')

    <div class="bg-white/80 backdrop-blur-md rounded-3xl shadow-xl p-7">

        <h2 class="text-2xl font-bold text-slate-800 text-center mb-2">
            Choose Difficulty
        </h2>

        <p class="text-slate-500 text-center mb-8">
            Select your challenge level
        </p>

        <form action="{{ route('game.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                <!-- Easy -->
                <label class="cursor-pointer">
                    <input type="radio" name="difficulty" value="easy" class="peer sr-only" required>

                    <div
                        class="flex flex-col items-center gap-3 p-6
                bg-emerald-50 hover:bg-emerald-100
                border-2 border-emerald-200 rounded-2xl
                transition peer-checked:border-emerald-500
                peer-checked:bg-emerald-100">

                        <span class="text-5xl">🐣</span>

                        <h3 class="font-bold text-xl text-emerald-800">
                            Easy
                        </h3>

                        <p class="text-sm text-emerald-700 text-center">
                            Baby Chick
                        </p>

                        <span class="text-sm font-semibold text-emerald-600">
                            8 Cards · 4 Pairs
                        </span>
                    </div>
                </label>

                <!-- Hard -->
                <label class="cursor-pointer">
                    <input type="radio" name="difficulty" value="hard" class="peer sr-only">

                    <div
                        class="flex flex-col items-center gap-3 p-6
                bg-amber-50 hover:bg-amber-100
                border-2 border-amber-200 rounded-2xl
                transition peer-checked:border-amber-500
                peer-checked:bg-amber-100">

                        <span class="text-5xl">🐓</span>

                        <h3 class="font-bold text-xl text-amber-800">
                            Hard
                        </h3>

                        <p class="text-sm text-amber-700 text-center">
                            Rooster
                        </p>

                        <span class="text-sm font-semibold text-amber-600">
                            15 Cards · 5 Triples
                        </span>
                    </div>
                </label>

            </div>

            <div class="flex justify-center mt-8">
                <button type="submit"
                    class="px-8 py-3 bg-blue-600 hover:bg-blue-700
            text-white font-bold rounded-xl transition
            cursor-pointer">
                    Start Game
                </button>
            </div>
        </form>

        <div class="flex justify-center mt-8">
            <a href="{{ route('menu') }}"
                class="px-6 py-3 bg-slate-100 hover:bg-slate-200
                        text-slate-700 font-semibold rounded-xl transition">
                ← Back to Menu
            </a>
        </div>

    </div>

    @include('layouts.footer')
@endsection
