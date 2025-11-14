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
                ->url(fn() => route('operasis.export.excel'))
                ->openUrlInNewTab(),

            Actions\Action::make('exportCsv')
                ->label('Export CSV')
                ->icon('heroicon-m-arrow-down-tray')
                ->url(fn() => route('operasis.export.excel', ['format' => 'csv']))
                ->openUrlInNewTab(),

            Actions\Action::make('exportPdf')
                ->label('Export PDF')
                ->icon('heroicon-m-document-arrow-down')
                ->url(fn() => route('operasis.export.pdf'))
                ->openUrlInNewTab(),
        ];
    }
}
