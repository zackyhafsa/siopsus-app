<?php

namespace App\Http\Controllers;

use App\Exports\OperasisExport;
use App\Models\Operasi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelWriter;

class OperasiExportController extends Controller
{
    // /admin/operasis/export/excel?from=...&to=...&status=...&jenis=...&format=xlsx|csv
    public function excel(Request $request)
    {
        $from   = $request->query('from');
        $to     = $request->query('to');
        $status = $request->query('status');   // 'sudah_bayar'|'belum_bayar'|null
        $jenis  = $request->query('jenis');    // 'R2'|'R4'|null
        $search = $request->query('search');   // string|null
        $format = $request->query('format', 'xlsx'); // xlsx|csv

        $export = new OperasisExport($from, $to, $status, $jenis, $search);

        $filename = 'laporan-operasi-' . now()->format('Ymd_His') . '.' . $format;
        $writerType = $format === 'csv' ? ExcelWriter::CSV : ExcelWriter::XLSX;

        return Excel::download($export, $filename, $writerType);
    }

    // /admin/operasis/export/pdf?from=...&to=...&status=...&jenis=...
    public function pdf(Request $request)
    {
        $from   = $request->query('from');
        $to     = $request->query('to');
        $status = $request->query('status');
        $jenis  = $request->query('jenis');
        $search = $request->query('search');

        $records = Operasi::query()
            ->when($from, fn($q) => $q->whereDate('tanggal_operasi', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('tanggal_operasi', '<=', $to))
            ->when($status, fn($q) => $q->where('status_pembayaran', $status))
            ->when($jenis, fn($q) => $q->where('jenis_kendaraan', $jenis))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('nama_penelusur', 'like', "%{$search}%")
                        ->orWhere('nomor_polisi', 'like', "%{$search}%")
                        ->orWhere('lokasi', 'like', "%{$search}%");
                });
            })
            ->latest('tanggal_operasi')
            ->get();

        $pdf = Pdf::loadView('exports.operasis', [
            'records'     => $records,
            'from'        => $from,
            'to'          => $to,
            'status'      => $status,
            'jenis'       => $jenis,
            'search'      => $search, // kirim ke view biar chip filter tampil
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        $filename = 'laporan-operasi-' . now()->format('Ymd_His') . '.pdf';
        return $pdf->stream($filename);
    }
}
