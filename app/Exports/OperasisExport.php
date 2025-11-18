<?php

namespace App\Exports;

use App\Models\Operasi;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OperasisExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(
        protected ?string $from = null,
        protected ?string $to = null,
        protected ?string $status = null,
        protected ?string $jenis = null,
        protected ?string $search = null,
    ) {}

    public function collection()
    {
        return Operasi::query()
            ->when($this->from, fn($q) => $q->whereDate('tanggal_operasi', '>=', $this->from))
            ->when($this->to, fn($q) => $q->whereDate('tanggal_operasi', '<=', $this->to))
            ->when($this->status, fn($q) => $q->where('status_pembayaran', $this->status))
            ->when($this->jenis, fn($q) => $q->where('jenis_kendaraan', $this->jenis))
            ->when($this->search, function ($q) {
                $q->where(function ($qq) {
                    $qq->where('nama_penelusur', 'like', "%{$this->search}%")
                        ->orWhere('nomor_polisi', 'like', "%{$this->search}%")
                        ->orWhere('lokasi', 'like', "%{$this->search}%");
                });
            })
            ->latest('tanggal_operasi')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal Operasi',
            'Nama Penelusur',
            'Nomor Polisi',
            'Jenis',
            'Jatuh Tempo',
            'Pokok PKB',
            'Denda PKB',
            'Opsen PKB',
            'Denda Opsen PKB',
            'Pokok SWDKLLJ',
            'Denda SWDKLLJ',
            'Status Pembayaran',
            'Total Tagihan',
        ];
    }

    public function map($r): array
    {
        $total = (int)($r->pokok_pkb ?? 0) + (int)($r->denda_pkb ?? 0)
            + (int)($r->opsen_pkb ?? 0) + (int)($r->denda_opsen_pkb ?? 0)
            + (int)($r->pokok_swdkllj ?? 0) + (int)($r->denda_swdkllj ?? 0);

        return [
            optional($r->tanggal_operasi)->format('Y-m-d H:i'),
            $r->nama_penelusur,
            $r->nomor_polisi,
            $r->jenis_kendaraan,
            optional($r->jatuh_tempo_pajak)->format('Y-m-d'),
            (int) $r->pokok_pkb,
            (int) $r->denda_pkb,
            (int) $r->opsen_pkb,
            (int) $r->denda_opsen_pkb,
            (int) $r->pokok_swdkllj,
            (int) $r->denda_swdkllj,
            $r->status_pembayaran === 'sudah_bayar' ? 'Sudah Dibayar' : 'Belum Dibayar',
            $total,
        ];
    }
}
