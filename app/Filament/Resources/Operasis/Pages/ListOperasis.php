<?php

namespace App\Filament\Resources\Operasis\Pages;

use App\Filament\Resources\Operasis\OperasiResource;
use App\Models\User;
use Filament\Actions;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListOperasis extends ListRecords
{
    protected static string $resource = OperasiResource::class;

    public function getHeading(): string
    {
        return "Laporan Operasi";
    }

    private function isAdmin(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();
        return $user?->isAdmin() ?? false;
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
                ->visible(fn() => $this->isAdmin())
                ->openUrlInNewTab(),
            Actions\Action::make('exportCsv')
                ->label('Export CSV')
                ->icon('heroicon-m-arrow-down-tray')
                ->url(fn() => route('operasis.export.excel', array_merge($this->buildExportQuery(), [
                    'format' => 'csv',
                ])))
                ->visible(fn() => $this->isAdmin())
                ->openUrlInNewTab(),

            Actions\Action::make('exportPdf')
                ->label('Export PDF')
                ->icon('heroicon-m-document-arrow-down')
                ->url(fn() => route('operasis.export.pdf', $this->buildExportQuery()))
                ->visible(fn() => $this->isAdmin())
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

    protected function getTableActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Hapus Data Operasi')
                ->modalDescription('Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.')
                ->modalSubmitActionLabel('Ya, Hapus')
                ->successNotificationTitle('Data berhasil dihapus'),
        ];
    }

    protected function getTableBulkActions(): array
    {
        return [
            Actions\DeleteBulkAction::make()
                ->requiresConfirmation()
                ->modalHeading('Hapus Data Terpilih')
                ->modalDescription('Apakah Anda yakin ingin menghapus semua data yang dipilih? Tindakan ini tidak dapat dibatalkan.')
                ->modalSubmitActionLabel('Ya, Hapus Semua')
                ->deselectRecordsAfterCompletion()
                ->successNotificationTitle('Data terpilih berhasil dihapus'),
        ];
    }
}
