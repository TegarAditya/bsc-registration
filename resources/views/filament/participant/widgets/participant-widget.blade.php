<x-filament-widgets::widget>
    <x-filament::section>
        <div>
            <h1 class="text-xl font-semibold">Pendaftaran Berhasil</h1>
            <hr class="my-2">
            <p class="text-sm">Selamat, Anda telah menjadi peserta Bupin Science Comptetition 2024.</p>
            <p class="font-semibold text-sm">Silahkan bergabung ke grup WhatsApp dibawah ini.</p>
        </div>
        <br class="my-2">
        <div>
            <x-filament::button href="{{ $this->getGroupLink() }}" tag="a">
                Group WhatsApp
            </x-filament::button>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
