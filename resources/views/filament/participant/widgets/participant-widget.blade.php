<x-filament-widgets::widget>
    <x-filament::section>
        <div>
            <h1 class="text-xl font-semibold">
                @if ($this->isAdmin())
                    Hey, Anda ini admin!
                @else
                    Pendaftaran Berhasil
                @endif
            </h1>
            <hr class="my-2">
            <p class="text-sm">
                @if ($this->isAdmin())
                    Selamat, Anda telah menjadi admin Bupin Science Competition 2024.
                @else
                    Selamat, Anda telah menjadi peserta Bupin Science Competition 2024.
                @endif
            </p>
            @if ($this->getUserStatus())
                <p class="font-semibold text-sm">Silahkan bergabung ke grup WhatsApp dibawah ini.</p>
            @else
                <p class="font-semibold text-sm">
                    @if ($this->isAdmin())
                        Anda adalah admin, silahkan menuju dashboard admin.
                    @else
                        Mohon lengkapi data diri anda terlebih dahulu.
                    @endif
                </p>
            @endif
        </div>
        <br class="my-2">
        <div>
            @if ($this->getUserStatus())
                <x-filament::button icon="heroicon-o-chat-bubble-left-right" href="{{ $this->getGroupLink() }}"
                    color="success" tag="a">
                    Group WhatsApp
                </x-filament::button>
            @elseif (!$this->getUserStatus() && !$this->isAdmin())
                <x-filament::button icon="heroicon-o-arrow-right" href="{{ $this->getProfileLink() }}" tag="a">
                    Lengkapi Data Diri
                </x-filament::button>
            @else
                <x-filament::button icon="heroicon-o-arrow-right" href="/admin" tag="a">
                    Dashboard Admin
                </x-filament::button>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
