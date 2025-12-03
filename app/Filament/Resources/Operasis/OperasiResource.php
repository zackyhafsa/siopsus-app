<?php

namespace App\Filament\Resources\Operasis;

use App\Filament\Resources\Operasis\Pages\CreateOperasi;
use App\Filament\Resources\Operasis\Pages\EditOperasi;
use App\Filament\Resources\Operasis\Pages\ListOperasis;
use App\Filament\Resources\Operasis\Schemas\OperasiForm;
use App\Filament\Resources\Operasis\Tables\OperasisTable;
use App\Models\Operasi;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;
use Filament\Schemas\Schema;
use BackedEnum;


class OperasiResource extends Resource
{
    protected static ?string $model = Operasi::class;

    // Lebih aman pakai string icon biasa
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $modelLabel = 'Operasi';
    protected static ?string $pluralModelLabel = 'Laporan Operasi';
    protected static ?string $navigationLabel = 'Laporan';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return OperasiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OperasisTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListOperasis::route('/'),
            'create' => CreateOperasi::route('/create'),
            'edit'   => EditOperasi::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $q = parent::getEloquentQuery();

        /** @var User|null $user */
        $user = Auth::user();

        return ($user?->isAdmin() ?? false) ? $q : $q->where('user_id', Auth::id());
    }
}
