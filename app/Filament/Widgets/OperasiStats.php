<?php

namespace App\Filament\Widgets;

use App\Models\Operasi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class OperasiStats extends BaseWidget
{
    protected function getStats(): array
    {
        $total = Operasi::count();
        $bulanIni = Operasi::whereBetween('tanggal_operasi', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $tagihanBulanIni = Operasi::whereBetween('tanggal_operasi', [now()->startOfMonth(), now()->endOfMonth()])
            ->get()
            ->sum(fn($r) => (int) (
                ($r->pokok_pkb ?? 0) + ($r->denda_pkb ?? 0) + ($r->opsen_pkb ?? 0) +
                ($r->denda_opsen_pkb ?? 0) + ($r->pokok_swdkllj ?? 0) + ($r->denda_swdkllj ?? 0)
            ));

        return [
            Stat::make('Total Operasi', (string) $total),
            Stat::make('Bulan Ini', (string) $bulanIni),
            Stat::make('Tagihan Bulan Ini', Number::currency($tagihanBulanIni, 'IDR', locale: 'id')),
        ];
    }
}
