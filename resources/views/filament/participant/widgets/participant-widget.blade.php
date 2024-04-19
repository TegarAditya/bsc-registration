<x-filament-widgets::widget>
    <x-filament::section>
        <div>
            <h1 class="text-xl font-semibold">Pendaftaran Berhasil</h1>
            <hr class="my-2">
            <p class="text-sm">Selamat, Anda telah menjadi peserta Bupin Science Comptetition 2024.</p>
            @if ($this->getUserStatus())
                <p class="font-semibold text-sm">Silahkan bergabung ke grup WhatsApp dibawah ini.</p>
            @else
                <p class="font-semibold text-sm">Mohon lengkapi data diri anda terlebih dahulu.</p>
            @endif
        </div>
        <br class="my-2">
        <div>
            @if ($this->getUserStatus())
                <x-filament::button href="{{ $this->getGroupLink() }}" tag="a">
                    Group WhatsApp
                </x-filament::button>
            @else
                <x-filament::button href="{{ $this->getProfileLink() }}" tag="a">
                    Lengkapi Data Diri
                </x-filament::button>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
