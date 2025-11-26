<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <h3 class="text-2xl font-semibold ">Filter</h3>

            <div class="flex items-center gap-2">
                {{-- Tombol preset --}}
                <x-filament::button :color="$range === 'day' ? 'primary' : 'gray'" wire:click="setRange('day')">
                    Hari ini
                </x-filament::button>
                <x-filament::button :color="$range === 'month' ? 'primary' : 'gray'" wire:click="setRange('month')">
                    Bulan ini
                </x-filament::button>
                <x-filament::button :color="$range === 'year' ? 'primary' : 'gray'" wire:click="setRange('year')">
                    Tahun ini
                </x-filament::button>

                {{-- Per-tanggal --}}
                <div class="flex items-center gap-2">
                    <x-filament::input.wrapper class="w-40">
                        <input type="date" wire:model.live="date"
                            class="fi-input block w-full rounded-lg border-gray-300 text-sm" />
                    </x-filament::input.wrapper>
                    <x-filament::badge :color="$range === 'date' ? 'primary' : 'gray'">
                        {{ $range === 'date' && $date ? \Illuminate\Support\Carbon::parse($date)->isoFormat('DD MMM YYYY') : 'Pilih Tanggal' }}
                    </x-filament::badge>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
