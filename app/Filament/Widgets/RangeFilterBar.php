<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class RangeFilterBar extends Widget
{
    protected ?string $heading = null;
    protected string $view = 'filament.widgets.range-filter-bar';

    public string $range = 'day'; // day|month|year
    public ?string $date = null;

    public function mount(): void
    {
        $this->date = now()->toDateString(); // default hari ini
    }

    // Dipanggil saat tombol diklik atau select berubah
    public function setRange(string $range, ?string $date = null): void
    {
        $this->range = in_array($range, ['day', 'month', 'year', 'date'], true) ? $range : 'day';
        $this->date  = $date ?? $this->date;

        // kirim 2 argumen POSISIONAL: (range, date)
        $this->dispatch('range-updated', $this->range, $this->date);
    }

    // Kalau kamu pakai <select wire:model.live="range">
    public function updatedDate($value): void
    {
        // validasi sederhana YYYY-MM-DD
        if (is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            $this->setRange('date', $value);
        }
    }
}
