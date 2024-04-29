<x-filament-widgets::widget>
    <x-filament::section>
        <div>
            <h1 class="text-xl font-semibold">
                Ujian Simulasi Tahap 1
            </h1>
            <hr class="my-2">
            <p class="text-sm">
                Bersiap untuk Simulasi Tahap Pertama BSC 2024...
            </p>
            @if (!$this->getUserStatus())
                <p class="font-semibold text-sm">Mohon lengkapi data diri anda terlebih dahulu!</p>
            @endif
        </div>
        <br class="my-2">
        <div>
            @if ($this->getUserStatus())
                <x-filament::button icon="heroicon-o-bolt" href="{{ $this->getExamLink() }}"
                    color="primary" tag="a" disabled>
                    <span id="countdown" class="font-mono">0d 0h 0m 0s</span>
                    <script>
                        var countDownDate = new Date({!! $this->getSchedule() !!}).getTime();
    
                        var x = setInterval(function() {
    
                            var now = new Date().getTime();
    
                            var distance = countDownDate - now;
    
                            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
                            document.getElementById("countdown").innerHTML = days + "d " + hours + "h " +
                                minutes + "m " + seconds + "s ";
    
                            if (distance < 0) {
                                clearInterval(x);
                                document.getElementById("countdown").innerHTML = "Mulai Simulasi";
                                document.getElementById("countdown").classList.remove("font-mono");
                            }
                        }, 1000);
                    </script>
                </x-filament::button>
            @else
                <x-filament::button icon="heroicon-o-arrow-right" href="{{ $this->getProfileLink() }}" tag="a">
                    Lengkapi Data Diri
                </x-filament::button>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
