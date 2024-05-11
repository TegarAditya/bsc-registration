<x-filament-widgets::widget>
    <x-filament::section>
        <div>
            <h1 class="text-xl font-semibold">
                {{ $this->getTitle() }}
            </h1>
            <hr class="my-2">
            @if (!$this->getUserStatus())
                <p class="font-semibold text-sm">Mohon lengkapi data diri anda terlebih dahulu!</p>
            @else
                <p class="text-sm">Terima kasih telah mengikuti Bupin Science Competition 2024.</p>
                <p class="font-semibold text-sm">Unduh sertifikat di sini!</p>
            @endif
        </div>
        <br class="my-2">
        <div class="flex space-x-2">
            @if ($this->getUserStatus())
                <x-filament::button icon="heroicon-o-arrow-down-tray" color="primary" wire:click="downloadCertificate">
                    <span>Download Sertifikat</span>
                </x-filament::button>
            @else
                <x-filament::button icon="heroicon-o-arrow-right" href="/edit-user-detail" tag="a">
                    Lengkapi Data Diri
                </x-filament::button>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
