<?php

namespace App\Filament\Resources\Operasis\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Number;
use Filament\Forms;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

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
                    ->sortable()
                    ->placeholder(null)
                    ->native(false),
            ])
            ->emptyStateHeading('Belum ada data operasi')
            ->emptyStateDescription('Klik tombol "Tambah Data" untuk memasukkan laporan operasi pertama.')
            ->emptyStateIcon('heroicon-o-clipboard-document-check')
            ->filters([
                Filter::make('rentang_tanggal')
                    ->label('Rentang Tanggal')
                    ->form([
                        DatePicker::make('from')
                            ->label('Dari')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                        DatePicker::make('to')
                            ->label('Sampai')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $from = $data['from'] ?? null;
                        $to   = $data['to'] ?? null;

                        return $query
                            ->when($from, fn(Builder $q) => $q->whereDate('tanggal_operasi', '>=', $from))
                            ->when($to,   fn(Builder $q) => $q->whereDate('tanggal_operasi', '<=', $to));
                    })
                    ->indicateUsing(function (array $data): ?string {
                        $from = $data['from'] ?? null;
                        $to   = $data['to'] ?? null;

                        if ($from && $to)   return "Tanggal: {$from} s/d {$to}";
                        if ($from)          return "Mulai: {$from}";
                        if ($to)            return "Sampai: {$to}";
                        return null;
                    }),

                Tables\Filters\SelectFilter::make('jenis_kendaraan')
                    ->label('Jenis Kendaraan')
                    ->options(['R2' => 'R2', 'R4' => 'R4']),

                Tables\Filters\SelectFilter::make('status_pembayaran')
                    ->label('Status Pembayaran')
                    ->options([
                        'belum_bayar' => 'Belum Dibayar',
                        'sudah_bayar' => 'Sudah Dibayar',
                    ]),
            ])
            ->recordAction(null)
            ->recordUrl(null)
            ->checkIfRecordIsSelectableUsing(fn() => true)
            ->defaultSort('tanggal_operasi', 'desc')
            ->bulkActions([
                DeleteBulkAction::make()
                    ->label('Hapus yang Dipilih')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Data Terpilih')
                    ->modalDescription('Apakah Anda yakin ingin menghapus semua data yang dipilih? Tindakan ini tidak dapat dibatalkan.')
                    ->modalSubmitActionLabel('Ya, Hapus Semua')
                    ->deselectRecordsAfterCompletion()
                    ->successNotificationTitle('Data terpilih berhasil dihapus'),
            ]);
    }
}
