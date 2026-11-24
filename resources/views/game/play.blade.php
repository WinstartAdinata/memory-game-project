@extends('welcome')

@section('content')
    @include('layouts.header')

    <div x-data="memoryGame()" class="bg-white/80 backdrop-blur-md rounded-3xl shadow-xl p-7">

        <h2 class="text-2xl font-bold text-slate-800 mb-4">
            Memory Game
        </h2>

        <div class="flex justify-between mb-6">
            <p>Difficulty: {{ $game->difficulty }}</p>
            <p>Score: {{ $game->score }}</p>
        </div>

        <div class="grid grid-cols-4 gap-4">

            @foreach ($game->gameCards as $card)
                <button type="button" @click="flipCard({{ $loop->index }})"
                    class="aspect-square rounded-xl overflow-hidden
                    border-2 border-blue-200 shadow-md
                    hover:scale-105 transition cursor-pointer">

                    <template x-if="cards[{{ $loop->index }}].isOpen">
                        <img src="{{ $card->image_url }}" alt="Memory Card" class="w-full h-full object-cover">
                    </template>

                    <template x-if="!cards[{{ $loop->index }}].isOpen">
                        <div
                            class="w-full h-full bg-gradient-to-br
                            from-blue-500 to-purple-500
                            flex items-center justify-center">

                            <span class="text-4xl">❓</span>

                        </div>
                    </template>

                </button>
            @endforeach

        </div>

    </div>

    @include('layouts.footer')
@endsection

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('memoryGame', () => ({

            cards: [],

            init() {
                this.cards = Array.from({
                        length: {{ $game->gameCards->count() }}
                    },
                    () => ({
                        isOpen: false,
                    })
                );
            },

            flipCard(index) {
                this.cards[index].isOpen = !this.cards[index].isOpen;
            },

        }));
    });
</script>
