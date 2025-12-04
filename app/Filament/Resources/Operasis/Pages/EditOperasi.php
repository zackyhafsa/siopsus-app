<?php

namespace App\Filament\Resources\Operasis\Pages;

use App\Filament\Resources\Operasis\OperasiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOperasi extends EditRecord
{
    protected static string $resource = OperasiResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl("index");
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
