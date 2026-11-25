@extends('welcome')

@section('content')
    @include('layouts.header')

    <div class="bg-white/80 backdrop-blur-md rounded-3xl shadow-xl p-7">

        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('menu') }}"
                class="px-6 py-3 bg-slate-100 hover:bg-slate-200
                            text-slate-700 font-semibold rounded-xl transition">
                ← Back to Menu
            </a>
        </div>

        @if ($games->isEmpty())
            <div class="text-center py-12">
                <div class="text-5xl mb-4">🎮</div>

                <h3 class="text-lg font-bold text-slate-700">
                    No Game History Yet
                </h3>

                <p class="text-sm text-slate-500 mt-2">
                    Your game records will appear here.
                </p>
            </div>
        @else
            <div class="space-y-3">

                @foreach ($games as $game)
                    <div
                        class="p-4 bg-violet-50 hover:bg-violet-100
                        border border-violet-100 rounded-2xl transition">

                        <div class="flex items-center justify-between mb-4">

                            <div>
                                <h3 class="font-bold text-lg text-violet-800">
                                    {{ $game->created_at->format('d M Y') }}
                                </h3>

                                <p class="text-sm text-violet-600">
                                    {{ $game->created_at->format('H:i') }}
                                </p>
                            </div>

                            <div class="flex flex-col items-end gap-2">

                                @if ($game->status === 'completed')
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-bold
                bg-emerald-100 text-emerald-700">
                                        Completed
                                    </span>
                                @else
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-bold
                bg-orange-100 text-orange-700">
                                        Incomplete
                                    </span>
                                @endif

                                <span
                                    class="px-3 py-1 rounded-full text-xs font-bold
            {{ $game->difficulty === 'easy' ? 'bg-emerald-100 text-emerald-700' : 'bg-orange-100 text-orange-700' }}">
                                    {{ ucfirst($game->difficulty) }}
                                </span>

                            </div>

                        </div>

                        <div class="grid grid-cols-3 gap-3">

                            <div class="bg-white/70 rounded-xl p-3 text-center">
                                <p class="text-sm text-slate-500">Moves</p>
                                <p class="text-xl font-bold text-slate-800">
                                    {{ $game->moves }}
                                </p>
                            </div>

                            <div class="bg-white/70 rounded-xl p-3 text-center">
                                <p class="text-sm text-slate-500">Score</p>
                                <p class="text-xl font-bold text-violet-700">
                                    {{ $game->score }}
                                </p>
                            </div>

                            <div class="bg-white/70 rounded-xl p-3 text-center">
                                <p class="text-sm text-slate-500">Timer</p>
                                <p class="text-xl font-bold text-slate-800">
                                    {{ gmdate('H:i:s', $game->elapsed_seconds) }}
                                </p>
                            </div>

                        </div>

                    </div>
                @endforeach

            </div>

            @if ($games->hasPages())
                <div class="flex items-center justify-between mt-6">

                    @if ($games->onFirstPage())
                        <span
                            class="px-4 py-2 text-sm font-semibold text-slate-400
                bg-white/50 border border-violet-100 rounded-xl cursor-not-allowed">
                            ← Previous
                        </span>
                    @else
                        <a href="{{ $games->previousPageUrl() }}"
                            class="px-4 py-2 text-sm font-semibold text-violet-700
                bg-white/80 border border-violet-200 rounded-xl
                hover:bg-violet-100 transition">
                            ← Previous
                        </a>
                    @endif

                    <span class="text-sm font-medium text-violet-700">
                        Page {{ $games->currentPage() }} of {{ $games->lastPage() }}
                    </span>

                    @if ($games->hasMorePages())
                        <a href="{{ $games->nextPageUrl() }}"
                            class="px-4 py-2 text-sm font-semibold text-violet-700
                bg-white/80 border border-violet-200 rounded-xl
                hover:bg-violet-100 transition">
                            Next →
                        </a>
                    @else
                        <span
                            class="px-4 py-2 text-sm font-semibold text-slate-400
                bg-white/50 border border-violet-100 rounded-xl cursor-not-allowed">
                            Next →
                        </span>
                    @endif

                </div>
            @endif

        @endif

    </div>

    @include('layouts.footer')
@endsection
