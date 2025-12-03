<?php

namespace App\Filament\Widgets;

use App\Models\Operasi;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Auth;

class OperasiStats extends BaseWidget
{
    protected ?string $heading = 'Ringkasan Operasi';

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        return $user?->isAdmin() ?? false;
    }

    // dengarkan event dari filter bar
    protected $listeners = ['range-updated' => 'applyRange'];

    public string $range = 'day';
    public ?string $date = null; // Y-m-d

    // TERIMA 2 ARGUMEN (jangan type-hint agar aman di Livewire)
    public function applyRange($range, $date = null): void
    {
        $this->range = in_array($range, ['day', 'month', 'year', 'date'], true) ? $range : 'day';
        $this->date  = $date ?: $this->date;
    }

    private function bounds(): array
    {
        $now = Carbon::now();

        return match ($this->range) {
            'day'   => [$now->copy()->startOfDay(),   $now->copy()->endOfDay()],
            'month' => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
            'year'  => [$now->copy()->startOfYear(),  $now->copy()->endOfYear()],
            'date'  => (function () {
                $d = $this->date ? Carbon::parse($this->date) : Carbon::now();
                return [$d->copy()->startOfDay(), $d->copy()->endOfDay()];
            })(),
            default => [$now->copy()->startOfDay(),   $now->copy()->endOfDay()],
        };
    }

    protected function getStats(): array
    {
        [$start, $end] = $this->bounds();

        $base = Operasi::whereBetween('tanggal_operasi', [$start, $end]);
        $r2 = (clone $base)->where('jenis_kendaraan', 'R2');
        $r4 = (clone $base)->where('jenis_kendaraan', 'R4');

        $r2Count = (clone $r2)->count();
        $r4Count = (clone $r4)->count();

        $pkbR2 = (int) ($r2->clone()->sum('pokok_pkb') ?? 0) + (int) ($r2->clone()->sum('denda_pkb') ?? 0);
        $pkbR4 = (int) ($r4->clone()->sum('pokok_pkb') ?? 0) + (int) ($r4->clone()->sum('denda_pkb') ?? 0);

        $opsR2 = (int) ($r2->clone()->sum('opsen_pkb') ?? 0) + (int) ($r2->clone()->sum('denda_opsen_pkb') ?? 0);
        $opsR4 = (int) ($r4->clone()->sum('opsen_pkb') ?? 0) + (int) ($r4->clone()->sum('denda_opsen_pkb') ?? 0);

        // Detail untuk setiap kategori
        $r2Details = $this->getDetailText($r2);
        $r4Details = $this->getDetailText($r4);

        return [
            Stat::make('Jumlah R2 yang diperiksa', (string) $r2Count)
                ->description('Unit')
                ->descriptionIcon('heroicon-m-information-circle')
                ->icon('heroicon-m-square-2-stack')
                ->color('info')
                ->url(route('filament.admin.resources.operasis.index', [
                    'tableFilters[jenis_kendaraan][value]' => 'R2',
                    'tableFilters[rentang_tanggal][from]' => $start->format('Y-m-d'),
                    'tableFilters[rentang_tanggal][to]' => $end->format('Y-m-d'),
                ]))
                ->extraAttributes([
                    'title' => $r2Details,
                    'class' => 'cursor-pointer hover:opacity-80',
                ]),

            Stat::make('Jumlah R4 yang diperiksa', (string) $r4Count)
                ->description('Unit')
                ->descriptionIcon('heroicon-m-information-circle')
                ->icon('heroicon-m-truck')
                ->color('info')
                ->url(route('filament.admin.resources.operasis.index', [
                    'tableFilters[jenis_kendaraan][value]' => 'R4',
                    'tableFilters[rentang_tanggal][from]' => $start->format('Y-m-d'),
                    'tableFilters[rentang_tanggal][to]' => $end->format('Y-m-d'),
                ]))
                ->extraAttributes([
                    'title' => $r4Details,
                    'class' => 'cursor-pointer hover:opacity-80',
                ]),

            Stat::make('Jumlah PKB & Denda PKB R2', Number::currency($pkbR2, 'IDR', locale: 'id'))
                ->description($this->getFinancialDetail($r2, 'PKB'))
                ->descriptionIcon('heroicon-m-information-circle')
                ->icon('heroicon-m-banknotes')
                ->color('success')
                ->url(route('filament.admin.resources.operasis.index', [
                    'tableFilters[jenis_kendaraan][value]' => 'R2',
                    'tableFilters[rentang_tanggal][from]' => $start->format('Y-m-d'),
                    'tableFilters[rentang_tanggal][to]' => $end->format('Y-m-d'),
                ]))
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:opacity-80',
                ]),

            Stat::make('Jumlah PKB & Denda PKB R4', Number::currency($pkbR4, 'IDR', locale: 'id'))
                ->description($this->getFinancialDetail($r4, 'PKB'))
                ->descriptionIcon('heroicon-m-information-circle')
                ->icon('heroicon-m-banknotes')
                ->color('success')
                ->url(route('filament.admin.resources.operasis.index', [
                    'tableFilters[jenis_kendaraan][value]' => 'R4',
                    'tableFilters[rentang_tanggal][from]' => $start->format('Y-m-d'),
                    'tableFilters[rentang_tanggal][to]' => $end->format('Y-m-d'),
                ]))
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:opacity-80',
                ]),

            Stat::make('Jumlah Opsen & Denda Opsen R2', Number::currency($opsR2, 'IDR', locale: 'id'))
                ->description($this->getFinancialDetail($r2, 'Opsen'))
                ->descriptionIcon('heroicon-m-information-circle')
                ->icon('heroicon-m-receipt-percent')
                ->color('warning')
                ->url(route('filament.admin.resources.operasis.index', [
                    'tableFilters[jenis_kendaraan][value]' => 'R2',
                    'tableFilters[rentang_tanggal][from]' => $start->format('Y-m-d'),
                    'tableFilters[rentang_tanggal][to]' => $end->format('Y-m-d'),
                ]))
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:opacity-80',
                ]),

            Stat::make('Jumlah Opsen & Denda Opsen R4', Number::currency($opsR4, 'IDR', locale: 'id'))
                ->description($this->getFinancialDetail($r4, 'Opsen'))
                ->descriptionIcon('heroicon-m-information-circle')
                ->icon('heroicon-m-receipt-percent')
                ->color('warning')
                ->url(route('filament.admin.resources.operasis.index', [
                    'tableFilters[jenis_kendaraan][value]' => 'R4',
                    'tableFilters[rentang_tanggal][from]' => $start->format('Y-m-d'),
                    'tableFilters[rentang_tanggal][to]' => $end->format('Y-m-d'),
                ]))
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:opacity-80',
                ]),
        ];
    }

    private function getDetailText($query): string
    {
        $penelusur = $query->clone()
            ->selectRaw('nama_penelusur, COUNT(*) as total')
            ->groupBy('nama_penelusur')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        if ($penelusur->isEmpty()) {
            return 'Tidak ada data';
        }

        $details = "Detail:\n";
        foreach ($penelusur as $item) {
            $details .= "- {$item->nama_penelusur}: {$item->total} unit\n";
        }

        return $details . "\nKlik untuk melihat detail lengkap";
    }

    private function getFinancialDetail($query, string $type): string
    {
        $count = $query->clone()->count();

        if ($type === 'PKB') {
            $pokok = (int) $query->clone()->sum('pokok_pkb');
            $denda = (int) $query->clone()->sum('denda_pkb');
        } else {
            $pokok = (int) $query->clone()->sum('opsen_pkb');
            $denda = (int) $query->clone()->sum('denda_opsen_pkb');
        }

        return sprintf(
            '%d unit | Pokok: %s | Denda: %s',
            $count,
            Number::currency($pokok, 'IDR', locale: 'id'),
            Number::currency($denda, 'IDR', locale: 'id')
        );
    }
}
