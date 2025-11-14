<?php

namespace App\Filament\Widgets;

use App\Models\Operasi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class OperasiOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalKendaraan = Operasi::count();

        $sudahBayar = Operasi::where('status_pembayaran', 'sudah_bayar')->count();
        $belumBayar = Operasi::where('status_pembayaran', 'belum_bayar')->count();

        // Potensi Pajak = pokok_pkb + opsen_pkb + pokok_swdkllj
        $potensiPajak =
            (int) Operasi::sum('pokok_pkb')
            + (int) Operasi::sum('opsen_pkb')
            + (int) Operasi::sum('pokok_swdkllj');

        // Potensi Denda = denda_pkb + denda_opsen_pkb + denda_swdkllj
        $potensiDenda =
            (int) Operasi::sum('denda_pkb')
            + (int) Operasi::sum('denda_opsen_pkb')
            + (int) Operasi::sum('denda_swdkllj');

        return [
            Stat::make('Total Kendaraan Diperiksa', (string) $totalKendaraan),
            Stat::make('Sudah Dibayar', (string) $sudahBayar),
            Stat::make('Belum Dibayar', (string) $belumBayar),

            Stat::make('Total Kendaraan Diperiksa', (string) $totalKendaraan),
            Stat::make('Total Potensi Pajak', Number::currency($potensiPajak, 'IDR', locale: 'id')),
            Stat::make('Total Potensi Denda', Number::currency($potensiDenda, 'IDR', locale: 'id')),
        ];
    }
}
