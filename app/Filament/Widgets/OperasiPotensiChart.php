<?php

namespace App\Filament\Widgets;

use App\Models\Operasi;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class OperasiPotensiChart extends ChartWidget
{
    protected ?string $heading = 'Potensi Pajak & Denda per Bulan';
    protected ?string $maxHeight = '320px';

    protected function getType(): string
    {
        return 'bar'; // batang untuk nominal rupiah
    }

    protected function getData(): array
    {
        $labels = [];
        $pajak  = [];
        $denda  = [];

        for ($i = 5; $i >= 0; $i--) {
            $start = Carbon::now()->subMonths($i)->startOfMonth();
            $end   = Carbon::now()->subMonths($i)->endOfMonth();

            $labels[] = $start->isoFormat('MMM Y');

            // Pajak = pokok_pkb + opsen_pkb + pokok_swdkllj
            $pajak[] =
                (int) Operasi::whereBetween('tanggal_operasi', [$start, $end])->sum('pokok_pkb')
                + (int) Operasi::whereBetween('tanggal_operasi', [$start, $end])->sum('opsen_pkb')
                + (int) Operasi::whereBetween('tanggal_operasi', [$start, $end])->sum('pokok_swdkllj');

            // Denda = denda_pkb + denda_opsen_pkb + denda_swdkllj
            $denda[] =
                (int) Operasi::whereBetween('tanggal_operasi', [$start, $end])->sum('denda_pkb')
                + (int) Operasi::whereBetween('tanggal_operasi', [$start, $end])->sum('denda_opsen_pkb')
                + (int) Operasi::whereBetween('tanggal_operasi', [$start, $end])->sum('denda_swdkllj');
        }

        return [
            'labels'   => $labels,
            'datasets' => [
                [
                    'label' => 'Pajak',
                    'data' => $pajak,
                    'backgroundColor' => 'red',
                    'borderColor' => 'red',
                ],
                ['label' => 'Denda', 'data' => $denda],
            ],
        ];
    }
}
