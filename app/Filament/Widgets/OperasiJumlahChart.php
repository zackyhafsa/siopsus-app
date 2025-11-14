<?php

namespace App\Filament\Widgets;

use App\Models\Operasi;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class OperasiJumlahChart extends ChartWidget
{
    protected  ?string $heading = 'Jumlah Kendaraan per Bulan';
    protected  ?string $maxHeight = '280px';

    protected function getType(): string
    {
        return 'line'; // atau 'bar' kalau suka batang
    }

    protected function getData(): array
    {
        $labels = [];
        $data   = [];

        for ($i = 5; $i >= 0; $i--) {
            $start = Carbon::now()->subMonths($i)->startOfMonth();
            $end   = Carbon::now()->subMonths($i)->endOfMonth();

            $labels[] = $start->isoFormat('MMM Y'); // contoh: "Nov 2025"
            $data[]   = Operasi::whereBetween('tanggal_operasi', [$start, $end])->count();
        }

        return [
            'labels'   => $labels,
            'datasets' => [
                [
                    'label' => 'Kendaraan Diperiksa',
                    'data'  => $data,
                    'tension' => 0.3,
                ],
            ],
        ];
    }
}
