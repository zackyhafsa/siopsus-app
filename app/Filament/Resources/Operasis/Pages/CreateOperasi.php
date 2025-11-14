<?php

namespace App\Filament\Resources\Operasis\Pages;

use App\Filament\Resources\Operasis\OperasiResource;
use Filament\Resources\Pages\CreateRecord;


class CreateOperasi extends CreateRecord
{
    protected static string $resource = OperasiResource::class;

    public function getHeading(): string
    {
        return "Tambah Laporan Operasi";
    }

    protected static bool $canCreateAnother = true;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl("index");
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return "Data Operasi Berhasil disimpan";
    }

    protected function getFormActions(): array
    {
        $actions = [
            $this->getCreateFormAction()
                ->label('Simpan')
                ->icon('heroicon-m-check')
        ];

        if (static::$canCreateAnother) {
            $actions[] = $this->getCreateAnotherFormAction()
                ->label('Simpan & Tambah Lagi');
        }

        $actions[] = $this->getCancelFormAction()
            ->label('Batal');

        return $actions;
    }
}
