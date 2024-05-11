<x-filament-widgets::widget>
    <x-filament::section>
        <div>
            <h1 class="text-xl font-semibold">
                {{ $this->getTitle() }}
            </h1>
            <hr class="my-2">
            <p class="text-sm">
                Berikut adalah jawaban dan pembahasan soal Bupin Science Competition 2024.
            </p>
            @if (!$this->getUserStatus())
                <p class="font-semibold text-sm">Mohon lengkapi data diri anda terlebih dahulu! </p>
            @endif
        </div>
        <br class="my-2">
        <div>
            @if ($this->getUserStatus())
            <div class="flex space-y-1 flex-wrap md:flex-none md:space-y-0 md:space-x-1">
                <x-filament::button icon="heroicon-o-light-bulb" href="{{ $this->getAnswerExplanationLink() }}" tag="a">
                    <span>Lihat Pembahasan</span>
                </x-filament::button>
                <x-filament::button icon="heroicon-o-bolt" href="{{ $this->getScoreLink() }}" tag="a">
                    <span>Lihat Nilai</span>
                </x-filament::button>
            </div>
            @else
                <x-filament::button icon="heroicon-o-arrow-right" href="/edit-user-detail" tag="a">
                    Lengkapi Data Diri
                </x-filament::button>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
