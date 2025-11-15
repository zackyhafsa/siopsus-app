<?php

namespace App\Filament\Resources\Operasis\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Number;
use Filament\Forms;

class OperasisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto_kendaraan')
                    ->label('Foto')
                    ->disk('public')
                    ->height(40)
                    ->width(40)
                    ->circular(),

                Tables\Columns\TextColumn::make('tanggal_operasi')
                    ->label('Tanggal')->dateTime('d M Y H:i')->sortable(),

                Tables\Columns\TextColumn::make('nama_penelusur')
                    ->label('Penelusur')->searchable()->sortable(),

                Tables\Columns\TextColumn::make('nomor_polisi')
                    ->label('No. Polisi')->searchable()->sortable(),

                \Filament\Tables\Columns\TextColumn::make('lokasi')
                    ->label('Lokasi')
                    ->limit(30)
                    ->tooltip(fn($state) => $state) // hover untuk lihat full
                    ->searchable()
                    ->url(function (\App\Models\Operasi $record) {
                        if (! $record->lokasi && ! ($record->latitude && $record->longitude)) {
                            return null;
                        }

                        // Prioritas: koordinat kalau ada, else alamat teks
                        $query = ($record->latitude && $record->longitude)
                            ? $record->latitude . ',' . $record->longitude
                            : $record->lokasi;

                        return 'https://www.google.com/maps/search/?api=1&query=Majalengka' . urlencode($query);
                    })
                    ->openUrlInNewTab(),

                Tables\Columns\TextColumn::make('jenis_kendaraan')
                    ->label('Jenis')->badge()->colors([
                        'warning' => 'R2',
                        'success' => 'R4',
                    ])->sortable(),

                Tables\Columns\TextColumn::make('jatuh_tempo_pajak')
                    ->label('Jatuh Tempo')->date('d M Y')->sortable(),

                Tables\Columns\TextColumn::make('total_tagihan')
                    ->label('Total Tagihan')
                    ->formatStateUsing(fn($state) => Number::currency((float) $state, 'IDR', locale: 'id')),
                \Filament\Tables\Columns\SelectColumn::make('status_pembayaran')
                    ->label('Status')
                    ->options([
                        'belum_bayar' => 'Belum Dibayar',
                        'sudah_bayar' => 'Sudah Dibayar',
                    ])
                    ->sortable(),
            ])
            ->emptyStateHeading('Belum ada data operasi')
            ->emptyStateDescription('Klik tombol "Tambah Data" untuk memasukkan laporan operasi pertama.')
            ->emptyStateIcon('heroicon-o-clipboard-document-check')
            ->filters([
                \Filament\Tables\Filters\Filter::make('rentang_tanggal')
                    ->label('Rentang Tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Dari'),
                        Forms\Components\DatePicker::make('to')->label('Sampai'),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data) {
                        return $query
                            ->when($data['from'] ?? null, fn($q, $from) => $q->whereDate('tanggal_operasi', '>=', $from))
                            ->when($data['to'] ?? null, fn($q, $to) => $q->whereDate('tanggal_operasi', '<=', $to));
                    }),
                \Filament\Tables\Filters\SelectFilter::make('jenis_kendaraan')
                    ->label('Jenis Kendaraan')
                    ->options(['R2' => 'R2', 'R4' => 'R4']),
                \Filament\Tables\Filters\SelectFilter::make('status_pembayaran')
                    ->label('Status Pembayaran')
                    ->options([
                        'belum_bayar' => 'Belum Dibayar',
                        'sudah_bayar' => 'Sudah Dibayar',
                    ]),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis_kendaraan')
                    ->label('Jenis Kendaraan')->options(['R2' => 'R2', 'R4' => 'R4']),
            ])
            ->headerActions([
                // \Filament\Tables\Actions\CreateAction::make()
                //     ->label('Tambah Data')
                //     ->icon('heroicon-m-plus')
                //     ->color('success'),

                // \Filament\Tables\Actions\Action::make('exportExcel')
                //     ->label('Export Excel')
                //     ->icon('heroicon-m-arrow-down-tray')
                //     ->url(fn() => route('operasis.export.excel')) // semua data
                //     ->openUrlInNewTab(),

                // \Filament\Tables\Actions\Action::make('exportCsv')
                //     ->label('Export CSV')
                //     ->icon('heroicon-m-arrow-down-tray')
                //     ->url(fn() => route('operisis.export.excel', ['format' => 'csv'])) // typo? pastikan route name tepat
                //     // perbaiki: harus route('operasis.export.excel', ['format' => 'csv'])
                //     ->url(fn() => route('operasis.export.excel', ['format' => 'csv']))
                //     ->openUrlInNewTab(),

                // \Filament\Tables\Actions\Action::make('exportPdf')
                //     ->label('Export PDF')
                //     ->icon('heroicon-m-document-arrow-down')
                //     ->url(fn() => route('operasis.export.pdf'))
                //     ->openUrlInNewTab(),
            ]);

        // ->actions([
        //     Tables\Actions\EditAction::make(),
        //     Tables\Actions\DeleteAction::make(),
        // ])
        // ->headerActions([
        //     Tables\Actions\CreateAction::make()->label('Tambah Data'),
        // ])
        // ->bulkActions([
        //     Tables\Actions\DeleteBulkAction::make(),
        // ])
        // ->defaultSort('tanggal_operasi', 'desc');
    }
}
