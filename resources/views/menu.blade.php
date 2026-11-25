@extends('welcome')

@section('content')
    @include('layouts.header')
    <!-- Main Menu -->
    <div class="bg-white/80 backdrop-blur-md rounded-3xl shadow-xl p-7">

        <h2 class="text-2xl font-bold text-slate-800 mb-6">
            Main Menu
        </h2>

        <div class="space-y-3">

            @if (isset($continueGame))
                <!-- Continue -->
                <a href="{{ route('game.play', $continueGame->id) }}"
                    class="flex items-center gap-4 p-4 bg-emerald-50
                    hover:bg-emerald-100 border border-emerald-100
                    rounded-2xl transition hover:-translate-y-1">

                    <span class="text-2xl bg-emerald-500 p-3 rounded-xl">▶️</span>

                    <div class="text-left">
                        <h3 class="font-bold text-lg text-emerald-800">Continue</h3>
                        <p class="text-sm text-emerald-600">Resume your last game</p>
                    </div>
                </a>
            @endif


            <!-- New Game -->
            <a href="{{ route('game.create') }}"
                class="flex items-center gap-4 p-4 bg-blue-50
                hover:bg-blue-100 border border-blue-100
                 rounded-2xl transition hover:-translate-y-1">

                <span class="text-2xl bg-blue-600 p-3 rounded-xl">🎮</span>

                <div class="text-left">
                    <h3 class="font-bold text-lg text-blue-800">New Game</h3>
                    <p class="text-sm text-blue-600">Start a new adventure</p>
                </div>
            </a>

            <!-- Leaderboard -->
            <a href="#leaderboard"
                class="flex items-center gap-4 p-4 bg-violet-50
                    hover:bg-violet-100 border border-violet-100
                    rounded-2xl transition hover:-translate-y-1">

                <span class="text-2xl bg-violet-400 p-3 rounded-xl">🏆</span>

                <div class="text-left">
                    <h3 class="font-bold text-lg text-violet-800">
                        Leaderboard
                    </h3>
                    <p class="text-sm text-violet-700">
                        See the top players
                    </p>
                </div>
            </a>

            <!-- History -->
            <a href="#history"
                class="flex items-center gap-4 p-4 bg-amber-50
                                                                hover:bg-amber-100 border border-amber-100
                                                                rounded-2xl transition hover:-translate-y-1">

                <span class="text-2xl bg-amber-400 p-3 rounded-xl">📜</span>

                <div class="text-left">
                    <h3 class="font-bold text-lg text-amber-800">History</h3>
                    <p class="text-sm text-amber-700">View your game records</p>
                </div>
            </a>

            <!-- Exit -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button type="submit"
                    class="flex items-center gap-4 p-4 bg-rose-50
                                                            hover:bg-rose-100 border border-rose-100 rounded-2xl
                                                            transition hover:-translate-y-1 w-full cursor-pointer">

                    <span class="text-2xl bg-rose-500 p-3 rounded-xl">🚪</span>

                    <div class="text-left">
                        <h3 class="font-bold text-lg text-rose-800">Exit</h3>
                        <p class="text-sm text-rose-600">Leave the game</p>
                    </div>
                </button>
            </form>

        </div>
    </div>
    @include('layouts.footer')
@endsection
