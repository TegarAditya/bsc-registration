<x-filament-widgets::widget>
    <x-filament::section>
        <div>
            <h1 class="text-xl font-semibold">
                Bupin Science Competition 2024
            </h1>
            <hr class="my-2">
            <p class="text-sm">
                Bersiap untuk pengerjaan BSC 2024 pada pukul 15.00 WIB secara serentak.
            </p>
            @if (!$this->getUserStatus())
                <p class="font-semibold text-sm">Mohon lengkapi data diri anda terlebih dahulu! Setelah melengkapi data diri mohon konfirmasi ke CP 🙏. </p>
            @endif
        </div>
        <br class="my-2">
        <div>
            @if ($this->getUserStatus())
                <x-filament::button icon="heroicon-o-bolt" href="{{ $this->getExamLink() }}"
                    color="primary" tag="a">
                    <span id="countdown" class="font-mono" disabled>Mulai Mengerjakan</span>
                </x-filament::button>
            @else
                <x-filament::button icon="heroicon-o-arrow-right" href="{{ $this->getProfileLink() }}" tag="a">
                    Lengkapi Data Diri
                </x-filament::button>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
