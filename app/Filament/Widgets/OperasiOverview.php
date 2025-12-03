<?php

namespace App\Filament\Widgets;

use App\Models\Operasi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Auth;

class OperasiOverview extends BaseWidget
{
    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->isAdmin();
    }
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

        // Detail breakdown
        $r2Count = Operasi::where('jenis_kendaraan', 'R2')->count();
        $r4Count = Operasi::where('jenis_kendaraan', 'R4')->count();

        $penelusurDetail = $this->getPenelusurDetail();
        $pajakDetail = $this->getPajakBreakdown();
        $dendaDetail = $this->getDendaBreakdown();

        return [
            Stat::make('Total Kendaraan Diperiksa', (string) $totalKendaraan)
                ->description(sprintf('R2: %d | R4: %d', $r2Count, $r4Count))
                ->descriptionIcon('heroicon-m-information-circle')
                ->icon('heroicon-m-clipboard-document-list')
                ->color('primary')
                ->url(route('filament.admin.resources.operasis.index'))
                ->extraAttributes([
                    'title' => $penelusurDetail,
                    'class' => 'cursor-pointer hover:opacity-80',
                ]),

            Stat::make('Sudah Dibayar', (string) $sudahBayar)
                ->description(sprintf('%.1f%% dari total', $totalKendaraan > 0 ? ($sudahBayar / $totalKendaraan * 100) : 0))
                ->descriptionIcon('heroicon-m-check-circle')
                ->icon('heroicon-m-check-badge')
                ->color('success')
                ->url(route('filament.admin.resources.operasis.index', [
                    'tableFilters[status_pembayaran][value]' => 'sudah_bayar',
                ]))
                ->extraAttributes([
                    'title' => $this->getStatusDetail('sudah_bayar'),
                    'class' => 'cursor-pointer hover:opacity-80',
                ]),

            Stat::make('Belum Dibayar', (string) $belumBayar)
                ->description(sprintf('%.1f%% dari total', $totalKendaraan > 0 ? ($belumBayar / $totalKendaraan * 100) : 0))
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->icon('heroicon-m-x-circle')
                ->color('danger')
                ->url(route('filament.admin.resources.operasis.index', [
                    'tableFilters[status_pembayaran][value]' => 'belum_bayar',
                ]))
                ->extraAttributes([
                    'title' => $this->getStatusDetail('belum_bayar'),
                    'class' => 'cursor-pointer hover:opacity-80',
                ]),

            Stat::make('Total Potensi Pajak', Number::currency($potensiPajak, 'IDR', locale: 'id'))
                ->description($pajakDetail)
                ->descriptionIcon('heroicon-m-information-circle')
                ->icon('heroicon-m-currency-dollar')
                ->color('success')
                ->url(route('filament.admin.resources.operasis.index'))
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:opacity-80',
                ]),

            Stat::make('Total Potensi Denda', Number::currency($potensiDenda, 'IDR', locale: 'id'))
                ->description($dendaDetail)
                ->descriptionIcon('heroicon-m-information-circle')
                ->icon('heroicon-m-exclamation-triangle')
                ->color('warning')
                ->url(route('filament.admin.resources.operasis.index'))
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:opacity-80',
                ]),
        ];
    }

    private function getPenelusurDetail(): string
    {
        $penelusur = Operasi::selectRaw('nama_penelusur, COUNT(*) as total')
            ->groupBy('nama_penelusur')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        if ($penelusur->isEmpty()) {
            return 'Tidak ada data';
        }

        $details = "Top Penelusur:\n";
        foreach ($penelusur as $item) {
            $details .= "- {$item->nama_penelusur}: {$item->total} unit\n";
        }

        return $details . "\nKlik untuk melihat detail lengkap";
    }

    private function getStatusDetail(string $status): string
    {
        $penelusur = Operasi::where('status_pembayaran', $status)
            ->selectRaw('nama_penelusur, COUNT(*) as total')
            ->groupBy('nama_penelusur')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        if ($penelusur->isEmpty()) {
            return 'Tidak ada data';
        }

        $statusText = $status === 'sudah_bayar' ? 'Sudah Bayar' : 'Belum Bayar';
        $details = "Detail {$statusText}:\n";
        foreach ($penelusur as $item) {
            $details .= "- {$item->nama_penelusur}: {$item->total} unit\n";
        }

        return $details . "\nKlik untuk melihat detail lengkap";
    }

    private function getPajakBreakdown(): string
    {
        $pokokPKB = (int) Operasi::sum('pokok_pkb');
        $opsenPKB = (int) Operasi::sum('opsen_pkb');
        $pokokSWDKLLJ = (int) Operasi::sum('pokok_swdkllj');

        return sprintf(
            'PKB: %s | Opsen: %s | SWDKLLJ: %s',
            Number::currency($pokokPKB, 'IDR', locale: 'id'),
            Number::currency($opsenPKB, 'IDR', locale: 'id'),
            Number::currency($pokokSWDKLLJ, 'IDR', locale: 'id')
        );
    }

    private function getDendaBreakdown(): string
    {
        $dendaPKB = (int) Operasi::sum('denda_pkb');
        $dendaOpsen = (int) Operasi::sum('denda_opsen_pkb');
        $dendaSWDKLLJ = (int) Operasi::sum('denda_swdkllj');

        return sprintf(
            'PKB: %s | Opsen: %s | SWDKLLJ: %s',
            Number::currency($dendaPKB, 'IDR', locale: 'id'),
            Number::currency($dendaOpsen, 'IDR', locale: 'id'),
            Number::currency($dendaSWDKLLJ, 'IDR', locale: 'id')
        );
    }
}
