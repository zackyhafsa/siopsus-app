<?php

namespace App\Filament\Resources\Operasis\Schemas;

use Filament\Schemas\Schema;

class OperasiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            // === Data Operasi ===
            \Filament\Forms\Components\DateTimePicker::make('tanggal_operasi')
                ->label('Tanggal Operasi')
                ->seconds(false)
                ->required(),

            \Filament\Forms\Components\TextInput::make('nama_penelusur')
                ->label('Nama Penelusur')
                ->required()
                ->maxLength(100),

            \Filament\Forms\Components\TextInput::make('nomor_polisi')
                ->label('Nomor Polisi')
                ->required()
                ->maxLength(20)
                ->unique(ignoreRecord: true)
                ->rule('regex:/^[A-Z]{1,2}\s?\d{1,4}\s?[A-Z]{0,3}$/'),

            // === Data Kendaraan & Pajak ===
            \Filament\Forms\Components\Select::make('jenis_kendaraan')
                ->label('Jenis Kendaraan')
                ->options(['R2' => 'R2 (Roda 2)', 'R4' => 'R4 (Roda 4)'])
                ->required(),

            \Filament\Forms\Components\DatePicker::make('jatuh_tempo_pajak')
                ->label('Jatuh Tempo Pajak')
                ->required(),

            \Filament\Forms\Components\TextInput::make('pokok_pkb')
                ->label('Pokok PKB')->numeric()->minValue(0)->default(0)->prefix('Rp')
                ->mask(\Filament\Support\RawJs::make('$money($input)'))
                ->stripCharacters([',', '.'])
                ->numeric()
                ->minValue(0)
                ->required()
                ->default(0),

            \Filament\Forms\Components\TextInput::make('denda_pkb')
                ->label('Denda PKB')->mask(\Filament\Support\RawJs::make('$money($input)'))
                ->stripCharacters([',', '.'])
                ->numeric()
                ->minValue(0)
                ->default(0),

            \Filament\Forms\Components\TextInput::make('opsen_pkb')
                ->label('Opsen PKB')->mask(\Filament\Support\RawJs::make('$money($input)'))
                ->stripCharacters([',', '.'])
                ->numeric()
                ->minValue(0)
                ->default(0)
                ->required(),

            \Filament\Forms\Components\TextInput::make('denda_opsen_pkb')
                ->label('Denda Opsen PKB')->mask(\Filament\Support\RawJs::make('$money($input)'))
                ->stripCharacters([',', '.'])
                ->numeric()
                ->minValue(0)
                ->default(0),

            \Filament\Forms\Components\TextInput::make('pokok_swdkllj')
                ->label('Pokok SWDKLLJ')->mask(\Filament\Support\RawJs::make('$money($input)'))
                ->stripCharacters([',', '.'])
                ->numeric()
                ->minValue(0)
                ->default(0)
                ->required(),

            \Filament\Forms\Components\TextInput::make('denda_swdkllj')
                ->label('Denda SWDKLLJ')->mask(\Filament\Support\RawJs::make('$money($input)'))
                ->stripCharacters([',', '.'])
                ->numeric()
                ->minValue(0)
                ->default(0),

            \Filament\Forms\Components\TextInput::make('lokasi')
                ->label('Lokasi (alamat/deskripsi)')
                ->placeholder('Contoh: Ds. Maja Selatan, Kec. Maja, Kab. Majalengka')
                ->maxLength(191)
                ->required(),

            \Filament\Forms\Components\Select::make('status_pembayaran')
                ->label('Status Pembayaran')
                ->options([
                    'belum_bayar' => 'Belum Dibayar',
                    'sudah_bayar' => 'Sudah Dibayar',
                ])
                ->required()
                ->default('belum_bayar')
                ->placeholder(null)
                ->native(false),

            \Filament\Forms\Components\FileUpload::make('foto_kendaraan')
                ->label('Foto Kendaraan')
                ->image()
                ->disk('public')
                ->directory('kendaraan')
                ->openable()
                ->downloadable()
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                ->maxSize(2048)
                ->imagePreviewHeight('250')
        ]);
    }
}
