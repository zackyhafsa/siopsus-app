<?php

namespace App\Filament\Resources\Operasis\Pages;

use App\Filament\Resources\Operasis\OperasiResource;
use Filament\Actions;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOperasis extends ListRecords
{
    protected static string $resource = OperasiResource::class;

    public function getHeading(): string
    {
        return "Laporan Operasi";
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label("Tambah Data")
                ->icon('heroicon-m-plus')
                ->modalHeading("Tambah Laporan Operasi")
                ->modalSubmitActionLabel("Simpan"),

            Actions\Action::make('exportExcel')
                ->label('Export Excel')
                ->icon('heroicon-m-arrow-down-tray')
                ->url(fn() => route('operasis.export.excel', array_merge($this->buildExportQuery(), [
                    'format' => 'xlsx',
                ])))
                ->openUrlInNewTab(),
            Actions\Action::make('exportCsv')
                ->label('Export CSV')
                ->icon('heroicon-m-arrow-down-tray')
                ->url(fn() => route('operasis.export.excel', array_merge($this->buildExportQuery(), [
                    'format' => 'csv',
                ])))
                ->openUrlInNewTab(),

            Actions\Action::make('exportPdf')
                ->label('Export PDF')
                ->icon('heroicon-m-document-arrow-down')
                ->url(fn() => route('operasis.export.pdf', $this->buildExportQuery()))
                ->openUrlInNewTab(),
        ];
    }

    protected function buildExportQuery(): array
    {
        $filters = $this->getTableFiltersForm()?->getState() ?? [];
        $search  = $this->getTableSearch();

        // Ambil nilai filter RENTANG TANGGAL
        $from = data_get($filters, 'rentang_tanggal.from');
        $to   = data_get($filters, 'rentang_tanggal.to');

        // Filter Select sederhana bisa jadi string langsung atau array ['value' => ...]
        $status = data_get($filters, 'status_pembayaran');
        if (is_array($status)) {
            $status = data_get($status, 'value');
        }

        $jenis = data_get($filters, 'jenis_kendaraan');
        if (is_array($jenis)) {
            $jenis = data_get($jenis, 'value');
        }

        // Hanya kirim yang terisi
        return array_filter([
            'from'   => $from,
            'to'     => $to,
            'status' => $status,
            'jenis'  => $jenis,
            'search' => $search,
        ], fn($v) => filled($v));
    }
}
