@extends('welcome')

@section('content')
    @include('layouts.header')

    <div x-data="{ activeTab: 'easy' }" class="bg-white/80 backdrop-blur-md rounded-3xl shadow-xl p-7">

        {{-- Page Title --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">
                    🏆 Leaderboard
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    See the best players and their records
                </p>
            </div>

            <a href="{{ route('menu') }}" class="text-sm font-semibold text-violet-600 hover:text-violet-800 transition">
                ← Back to Menu
            </a>
        </div>

        {{-- Tabs --}}
        <div class="flex gap-3 mb-7">

            <button @click="activeTab = 'easy'"
                :class="activeTab === 'easy'
                    ?
                    'bg-emerald-500 text-white shadow-md' :
                    'bg-emerald-50 text-emerald-700 hover:bg-emerald-100'"
                class="flex-1 py-3 rounded-xl font-bold transition cursor-pointer">
                Easy
            </button>

            <button @click="activeTab = 'hard'"
                :class="activeTab === 'hard'
                    ?
                    'bg-rose-500 text-white shadow-md' :
                    'bg-rose-50 text-rose-700 hover:bg-rose-100'"
                class="flex-1 py-3 rounded-xl font-bold transition cursor-pointer">
                Hard
            </button>

        </div>

        {{-- EASY TAB --}}
        <div x-show="activeTab === 'easy'" x-cloak>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Highest Score --}}
                <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-5">

                    <h3 class="text-lg font-bold text-emerald-800 mb-4">
                        🏆 Highest Score
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">

                            <thead>
                                <tr class="text-sm text-emerald-700 border-b border-emerald-200">
                                    <th class="py-3 px-2">Rank</th>
                                    <th class="py-3 px-2">Username</th>
                                    <th class="py-3 px-2 text-right">Score</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($easyScores as $index => $player)
                                    <tr class="border-b border-emerald-100 last:border-0 text-slate-700">
                                        <td class="py-3 px-2 font-semibold">
                                            {{ $index + 1 }}
                                        </td>

                                        <td class="py-3 px-2 font-medium">
                                            {{ Str::words($player->user->name, 2, '') }}
                                        </td>

                                        <td class="py-3 px-2 text-right font-bold text-emerald-700">
                                            {{ $player->score }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-6 text-center text-sm text-slate-500">
                                            No records yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>

                {{-- Fastest Time --}}
                <div class="bg-violet-50 border border-violet-100 rounded-2xl p-5">

                    <h3 class="text-lg font-bold text-violet-800 mb-4">
                        ⏱️ Fastest Time
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">

                            <thead>
                                <tr class="text-sm text-violet-700 border-b border-violet-200">
                                    <th class="py-3 px-2">Rank</th>
                                    <th class="py-3 px-2">Username</th>
                                    <th class="py-3 px-2 text-right">Time</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($easyTimes as $index => $player)
                                    <tr class="border-b border-violet-100 last:border-0 text-slate-700">
                                        <td class="py-3 px-2 font-semibold">
                                            {{ $index + 1 }}
                                        </td>

                                        <td class="py-3 px-2 font-medium">
                                            {{ Str::words($player->user->name, 2, '') }}
                                        </td>

                                        <td class="py-3 px-2 text-right font-bold text-violet-700 whitespace-nowrap">
                                            {{ gmdate('H:i:s', $player->time) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-6 text-center text-sm text-slate-500">
                                            No records yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>

            </div>
        </div>

        {{-- HARD TAB --}}
        <div x-show="activeTab === 'hard'" x-cloak>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Highest Score --}}
                <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-5">

                    <h3 class="text-lg font-bold text-emerald-800 mb-4">
                        🏆 Highest Score
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">

                            <thead>
                                <tr class="text-sm text-emerald-700 border-b border-emerald-200">
                                    <th class="py-3 px-2">Rank</th>
                                    <th class="py-3 px-2">Username</th>
                                    <th class="py-3 px-2 text-right">Score</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($hardScores as $index => $player)
                                    <tr class="border-b border-emerald-100 last:border-0 text-slate-700">
                                        <td class="py-3 px-2 font-semibold">
                                            {{ $index + 1 }}
                                        </td>

                                        <td class="py-3 px-2 font-medium">
                                            {{ Str::words($player->user->name, 2, '') }}
                                        </td>

                                        <td class="py-3 px-2 text-right font-bold text-emerald-700">
                                            {{ $player->score }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-6 text-center text-sm text-slate-500">
                                            No records yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>

                {{-- Fastest Time --}}
                <div class="bg-violet-50 border border-violet-100 rounded-2xl p-5">

                    <h3 class="text-lg font-bold text-violet-800 mb-4">
                        ⏱️ Fastest Time
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">

                            <thead>
                                <tr class="text-sm text-violet-700 border-b border-violet-200">
                                    <th class="py-3 px-2">Rank</th>
                                    <th class="py-3 px-2">Username</th>
                                    <th class="py-3 px-2 text-right">Time</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($hardTimes as $index => $player)
                                    <tr class="border-b border-violet-100 last:border-0 text-slate-700">
                                        <td class="py-3 px-2 font-semibold">
                                            {{ $index + 1 }}
                                        </td>

                                        <td class="py-3 px-2 font-medium">
                                            {{ Str::words($player->user->name, 2, '') }}
                                        </td>

                                        <td class="py-3 px-2 text-right font-bold text-violet-700 whitespace-nowrap">
                                            {{ gmdate('H:i:s', $player->time) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-6 text-center text-sm text-slate-500">
                                            No records yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>

    @include('layouts.footer')
@endsection
