@extends('welcome')

@section('content')
    @include('layouts.header')

    <div x-data="memoryGame()" class="bg-white/80 backdrop-blur-md rounded-3xl shadow-xl p-7">

        <h2 class="text-2xl font-bold text-slate-800 mb-6">
            Memory Game
        </h2>

        <div class="flex justify-between items-center mb-6">

            <form action="{{ route('game.pause', $game->id) }}" method="POST">
                @csrf

                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2
            bg-slate-100 hover:bg-slate-200
            text-slate-700 font-semibold rounded-xl transition">
                    <span>←</span>
                    <span>Back to Menu</span>
                </button>
            </form>

        </div>

        <div class="grid grid-cols-3 gap-3 mb-6">

            <div class="bg-blue-50 rounded-xl p-3 text-center">
                <p class="text-sm text-blue-600">Difficulty</p>
                <p class="font-bold text-blue-800">
                    {{ ucfirst($game->difficulty) }}
                </p>
            </div>

            <div class="bg-emerald-50 rounded-xl p-3 text-center">
                <p class="text-sm text-emerald-600">Score</p>
                <p class="font-bold text-emerald-800" x-text="score"></p>
            </div>

            <div class="bg-amber-50 rounded-xl p-3 text-center">
                <p class="text-sm text-amber-600">Moves</p>
                <p class="font-bold text-amber-800" x-text="moves"></p>
            </div>

        </div>

        <div x-show="!imagesLoaded" x-cloak class="py-16 text-center">

            <div class="text-4xl mb-3 animate-pulse">🃏</div>

            <p class="text-slate-600 font-semibold">
                Loading game images...
            </p>

            <p class="text-sm text-slate-400 mt-2">
                Please wait a moment
            </p>

        </div>

        <div x-show="imagesLoaded" x-cloak>

            @if ($game->difficulty === 'easy')
                <div class="grid grid-cols-4 gap-4">
            @else
                    <div class="grid grid-cols-5 gap-4">
                @endif

                    @foreach ($game->gameCards as $card)
                        <button type="button" @click="flipCard({{ $loop->index }})" :disabled="cards[{{ $loop->index }}].isMatched ||
                                                                    isChecking ||
                                                                    isFinished" class="aspect-square rounded-xl overflow-hidden
                                                                    border-2 border-blue-200 shadow-md
                                                                    hover:scale-105 transition cursor-pointer
                                                                    disabled:cursor-not-allowed">

                            <template x-if="cards[{{ $loop->index }}].isOpen">
                                <img :src="cards[{{ $loop->index }}].image_url" alt="Memory Card"
                                    class="w-full h-full object-cover" decoding="async">
                            </template>

                            <template x-if="!cards[{{ $loop->index }}].isOpen">
                                <div class="w-full h-full bg-linear-to-br
                                                                            from-blue-500 to-purple-500
                                                                            flex items-center justify-center">

                                    <span class="text-4xl">❓</span>

                                </div>
                            </template>

                        </button>
                    @endforeach

                </div>

            </div>

            <div x-show="isFinished" x-cloak class="bg-emerald-50 border border-emerald-200
                                    rounded-2xl p-6 my-6 text-center">

                <div class="text-5xl mb-3">🎉</div>

                <h3 class="text-2xl font-bold text-emerald-700">
                    Game Completed!
                </h3>

                <p class="text-emerald-600 mt-2">
                    Congratulations! You matched all the cards.
                </p>

                <div class="flex justify-center gap-6 mt-4">

                    <div>
                        <p class="text-sm text-emerald-600">Final Score</p>
                        <p class="text-2xl font-bold text-emerald-800" x-text="score"></p>
                    </div>

                    <div>
                        <p class="text-sm text-emerald-600">Total Moves</p>
                        <p class="text-2xl font-bold text-emerald-800" x-text="moves"></p>
                    </div>

                </div>

                <a href="{{ route('game.create') }}" class="inline-block mt-6 px-8 py-3 bg-blue-600
                                        hover:bg-blue-700 text-white font-bold
                                        rounded-xl transition">
                    Next Game
                </a>

            </div>

            <div x-show="errorMessage" x-cloak class="my-4 p-3 rounded-xl bg-rose-50 text-rose-700 text-sm"
                x-text="errorMessage">
            </div>

        </div>

        @include('layouts.footer')
@endsection

    <script>
        document.addEventListener('alpine:init', () => {

            Alpine.data('memoryGame', () => ({

                maxOpen: {{ $game->difficulty === 'easy' ? 2 : 3 }},

                cards: [],

                score: {{ $game->score }},
                moves: {{ $game->moves }},

                selectedCards: [],
                isChecking: false,
                isFinished: {{ $game->status === 'completed' ? 'true' : 'false' }},
                errorMessage: '',

                imagesLoaded: false,

                async init() {

                    this.cards = @js(
                        $game->gameCards
                            ->map(fn($card) => [
                                'id' => $card->id,
                                'group_key' => $card->group_key,
                                'image_url' => $card->image_url,
                                'isOpen' => $card->is_matched,
                                'isMatched' => $card->is_matched,
                            ])
                            ->values()
                    );

                    await this.preloadImages();

                },

                async preloadImages() {

                    const imageUrls = [
                        ...new Set(this.cards.map(card => card.image_url))
                    ];

                    const promises = imageUrls.map(url => {

                        return new Promise(resolve => {

                            const image = new Image();

                            image.onload = resolve;
                            image.onerror = resolve;

                            image.src = url;

                            if (image.complete) {
                                resolve();
                            }

                        });

                    });

                    await Promise.all(promises);

                    this.imagesLoaded = true;

                },

                flipCard(index) {

                    const card = this.cards[index];

                    if (
                        card.isOpen ||
                        card.isMatched ||
                        this.isChecking ||
                        this.isFinished
                    ) {
                        return;
                    }

                    card.isOpen = true;

                    this.selectedCards.push(index);

                    if (this.selectedCards.length === this.maxOpen) {
                        this.checkMatch();
                    }

                },

                async checkMatch() {

                    this.isChecking = true;
                    this.errorMessage = '';

                    const selectedIndexes = [...this.selectedCards];

                    const selectedCardIds = selectedIndexes.map(index => {
                        return this.cards[index].id;
                    });

                    try {

                        const response = await fetch(
                            @json(route('game.move', $game->id)), {
                            method: 'POST',

                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': @json(csrf_token()),
                            },

                            body: JSON.stringify({
                                cards: selectedCardIds,
                            }),
                        }
                        );

                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.message || 'Failed to save game progress.');
                        }

                        this.score = data.score;
                        this.moves = data.moves;

                        if (data.matched) {

                            selectedIndexes.forEach(index => {
                                this.cards[index].isMatched = true;
                            });

                            this.selectedCards = [];
                            this.isFinished = data.completed;
                            this.isChecking = false;

                        } else {

                            setTimeout(() => {

                                selectedIndexes.forEach(index => {
                                    this.cards[index].isOpen = false;
                                });

                                this.selectedCards = [];
                                this.isChecking = false;

                            }, 1000);

                        }

                    } catch (error) {

                        console.error(error);

                        this.errorMessage = error.message;

                        selectedIndexes.forEach(index => {
                            this.cards[index].isOpen = false;
                        });

                        this.selectedCards = [];
                        this.isChecking = false;

                    }

                },

                checkGameFinished() {

                    this.isFinished = this.cards.every(card => {
                        return card.isMatched;
                    });

                },

            }));

        });
    </script>
