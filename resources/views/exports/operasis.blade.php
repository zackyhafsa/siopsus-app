@php
// helper format angka
$fmt = fn($n) => number_format((int) $n, 0, ',', '.');

// hitung ringkasan
$countTotal = count($records);
$countSudah = 0; $countBelum = 0;
$sumPokok = $sumDenda = $sumOpsen = $sumOpsenDenda = $sumSwd = $sumSwdDenda = 0;

foreach ($records as $r) {
$countSudah += ($r->status_pembayaran === 'sudah_bayar') ? 1 : 0;
$countBelum += ($r->status_pembayaran === 'belum_bayar') ? 1 : 0;

$sumPokok += (int) ($r->pokok_pkb ?? 0);
$sumDenda += (int) ($r->denda_pkb ?? 0);
$sumOpsen += (int) ($r->opsen_pkb ?? 0);
$sumOpsenDenda += (int) ($r->denda_opsen_pkb ?? 0);
$sumSwd += (int) ($r->pokok_swdkllj ?? 0);
$sumSwdDenda += (int) ($r->denda_swdkllj ?? 0);
}

$totalPajak = $sumPokok + $sumOpsen + $sumSwd; // potensi pajak
$totalDenda = $sumDenda + $sumOpsenDenda + $sumSwdDenda; // potensi denda
$grandTotal = $totalPajak + $totalDenda;

// filter chips text
$statusLabel = $status === 'sudah_bayar' ? 'Sudah Dibayar' : ($status === 'belum_bayar' ? 'Belum Dibayar' : '-');
@endphp
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Operasi - SIOPSUS</title>
    <style>
        @page { margin: 95px 22px 52px 22px; } 

        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #111; }

        /* Header / Footer (fixed, tampil di setiap halaman) */
        .pdf-header {
            position: fixed;
            top: -80px;
            left: 0;
            right: 0;
            height: 80px;
            border-bottom: 2px solid #016630;
        }

        .brand {
            float: left;
            width: 60%;
        }

        .brand h1 {
            margin: 8px 0 2px;
            font-size: 18px;
            color: #016630;
            letter-spacing: .5px;
        }

        .brand .sub {
            font-size: 11px;
            color: #444;
        }

        .brand .meta {
            font-size: 10px;
            color: #555;
            margin-top: 4px;
        }

        .logo {
            float: right;
            text-align: right;
            width: 40%;
        }

        .logo img {
            height: 36px;
        }

        .pdf-footer {
            position: fixed;
            bottom: -40px;
            left: 0;
            right: 0;
            height: 40px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 10px;
            display: table;
            width: 100%;
        }

        .pdf-footer .left,
        .pdf-footer .right {
            display: table-cell;
            vertical-align: middle;
        }

        .pdf-footer .right {
            text-align: right;
        }

        /* Cards */
        .cards {
            width: 100%;
            margin: 6px 0 10px;
            border-collapse: separate;
            border-spacing: 8px;
        }

        .card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 8px 10px;
            background: #fafafa;
        }

        .card .label {
            font-size: 10px;
            color: #555;
            margin-bottom: 4px;
        }

        .card .value {
            font-size: 16px;
            font-weight: bold;
            color: #111;
        }

        .card.accent {
            background: #f0fdf4;
            border-color: #86efac;
        }

        /* hijau lembut */
        .card.warn {
            background: #fff7ed;
            border-color: #fdba74;
        }

        /* oranye lembut */

        /* Filter chips */
        .chips {
            margin: 20px 0 10px;
        }

        .chip {
            display: inline-block;
            font-size: 10px;
            padding: 3px 8px;
            border-radius: 999px;
            background: #eef2ff;
            color: #3730a3;
            margin-right: 6px;
            border: 1px solid #c7d2fe;
        }
        .col-tgl   { width: 9%; }    /* Tanggal */
        .col-nama  { width: 10%; }   /* Penelusur */
        .col-nopol { width: 7%; }    /* No. Polisi */
        .col-jenis { width: 3.5%; }    /* Jenis - diperkecil */
        .col-jth   { width: 7%; }    /* Jatuh Tempo */
        .col-rp    { width: 7.5%; }  /* Setiap kolom uang - diperbesar */
        .col-stat  { width: 7%; }    /* Status */
        .col-total { width: 8.5%; }  /* Total - diperbesar */

        .wrap { 
            white-space: normal; 
            word-break: break-word;
            line-height: 1.2;
        }
        .nowrap { white-space: nowrap; }
        .right { text-align: right; }
        .center { text-align: center; }

        /* Tabel data */
        table.data {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .data thead {
            display: table-header-group;
        }

        /* ulang header setiap halaman */
        .data tfoot {
            display: table-row-group;
        }

        .data th,
        .data td { border: 1px solid #ddd; padding: 4px 5px; } 

        .data th {
            background: #f3f4f6;
            color: #111;
            font-size: 10px;
            line-height: 1.3;
            white-space: normal;
            word-wrap: break-word;
            vertical-align: middle;
        }

        .data tbody tr:nth-child(even) {
            background: #fafafa;
        }

        .right {
            text-align: right;
            white-space: nowrap;
        }

        .center {
            text-align: center;
        }

        .status-badge {
            display: inline-block;
            padding: 2px;
            border-radius: 6px;
            font-size: 10px;
            color: #fff;
        }

        .status-sudah {
            background: #16a34a;
        }

        /* green */
        .status-belum {
            background: #ef4444;
        }

        /* red */

        /* watermark halus (opsional) */
        .wm {
            position: fixed;
            top: 40%;
            left: 10%;
            right: 10%;
            text-align: center;
            color: rgba(1, 102, 48, 0.05);
            font-size: 80px;
            transform: rotate(-15deg);
        }
    </style>
</head>

<body>

    <div class="pdf-header">
        <div class="brand">
            <h1>SIOPSUS</h1>
            <div class="sub">Sistem Informasi Operasi Khusus</div>
            <div class="meta">
                Laporan Operasi — Dibuat: {{ $generatedAt->format('Y-m-d H:i') }}
            </div>
        </div>
        <div class="logo">
            {{-- taruh logo di public/images/siopsus-logo.png bila ada --}}
            @php $logo = public_path('images/siopsus-logo.png'); @endphp
            @if (file_exists($logo))
            <img src="{{ $logo }}" alt="Logo">
            @endif
        </div>
    </div>

    <div class="pdf-footer">
        <div class="left">© {{ date('Y') }} SIOPSUS</div>
        <div class="right">Halaman {PAGE_NUM} / {PAGE_COUNT}</div>
    </div>

    {{-- Watermark opsional --}}
    {{-- <div class="wm">SIOPSUS</div> --}}

    {{-- Filter chips --}}
    <div class="chips">
        <span class="chip">Rentang: {{ $from ?? '-' }} s/d {{ $to ?? '-' }}</span>
        <span class="chip">Status: {{ $statusLabel }}</span>
        <span class="chip">Jenis: {{ $jenis ?? '-' }}</span>
    </div>

    {{-- Cards ringkasan --}}
    <table class="cards">
        <tr>
            <td class="card">
                <div class="label">Total Kendaraan Diperiksa</div>
                <div class="value">{{ $fmt($countTotal) }}</div>
            </td>
            <td class="card accent">
                <div class="label">Sudah Dibayar</div>
                <div class="value">{{ $fmt($countSudah) }}</div>
            </td>
            <td class="card warn">
                <div class="label">Belum Dibayar</div>
                <div class="value">{{ $fmt($countBelum) }}</div>
            </td>
            <td class="card">
                <div class="label">Potensi Pajak</div>
                <div class="value">Rp {{ $fmt($totalPajak) }}</div>
            </td>
            <td class="card">
                <div class="label">Potensi Denda</div>
                <div class="value">Rp {{ $fmt($totalDenda) }}</div>
            </td>
            <td class="card">
                <div class="label">Grand Total</div>
                <div class="value">Rp {{ $fmt($grandTotal) }}</div>
            </td>
        </tr>
    </table>

    {{-- Tabel data --}}
    <table class="data">
        <thead>
            <tr>
                <th class="col-tgl wrap">Tanggal</th>
                <th class="col-nama wrap">Penelusur</th>
                <th class="col-nopol wrap">No. Polisi</th>
                <th class="col-jenis center wrap">Jenis</th>
                <th class="col-jth wrap">Jth<br>Tempo</th>
                <th class="col-rp right wrap">Pokok<br>PKB</th>
                <th class="col-rp right wrap">Denda<br>PKB</th>
                <th class="col-rp right wrap">Opsen<br>PKB</th>
                <th class="col-rp right wrap">Denda<br>Opsen</th>
                <th class="col-rp right wrap">Pokok<br>SWDKLLJ</th>
                <th class="col-rp right wrap">Denda<br>SWDKLLJ</th>
                <th class="col-stat center wrap">Status</th>
                <th class="col-total right wrap">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($records as $r)
            @php
            $total = (int)($r->pokok_pkb ?? 0) + (int)($r->denda_pkb ?? 0)
            + (int)($r->opsen_pkb ?? 0) + (int)($r->denda_opsen_pkb ?? 0)
            + (int)($r->pokok_swdkllj ?? 0) + (int)($r->denda_swdkllj ?? 0);
            $badgeClass = $r->status_pembayaran === 'sudah_bayar' ? 'status-sudah' : 'status-belum';
            $badgeText = $r->status_pembayaran === 'sudah_bayar' ? 'Sudah Dibayar' : 'Belum Dibayar';
            @endphp
            <tr>
                <td>{{ optional($r->tanggal_operasi)->format('Y-m-d H:i') }}</td>
                <td>{{ $r->nama_penelusur }}</td>
                <td>{{ $r->nomor_polisi }}</td>
                <td class="center">{{ $r->jenis_kendaraan }}</td>
                <td>{{ optional($r->jatuh_tempo_pajak)->format('Y-m-d') }}</td>
                <td class="right">{{ $fmt($r->pokok_pkb) }}</td>
                <td class="right">{{ $fmt($r->denda_pkb) }}</td>
                <td class="right">{{ $fmt($r->opsen_pkb) }}</td>
                <td class="right">{{ $fmt($r->denda_opsen_pkb) }}</td>
                <td class="right">{{ $fmt($r->pokok_swdkllj) }}</td>
                <td class="right">{{ $fmt($r->denda_swdkllj) }}</td>
                <td class="center"><span class="status-badge {{ $badgeClass }}">{{ $badgeText }}</span></td>
                <td class="right"><strong>Rp {{ $fmt($total) }}</strong></td>
            </tr>
            @empty
            <tr>
                <td colspan="13" class="center" style="color:#666;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="right">TOTAL</th>
                <th class="right">Rp {{ $fmt($sumPokok) }}</th>
                <th class="right">Rp {{ $fmt($sumDenda) }}</th>
                <th class="right">Rp {{ $fmt($sumOpsen) }}</th>
                <th class="right">Rp {{ $fmt($sumOpsenDenda) }}</th>
                <th class="right">Rp {{ $fmt($sumSwd) }}</th>
                <th class="right">Rp {{ $fmt($sumSwdDenda) }}</th>
                <th class="center">—</th>
                <th class="right"><strong>Rp {{ $fmt($grandTotal) }}</strong></th>
            </tr>
        </tfoot>
    </table>

    {{-- nomor halaman DomPDF --}}
    <script type="text/php">
        if (isset($pdf)) {
    $text = "Halaman {PAGE_NUM} / {PAGE_COUNT}";
    $font = $fontMetrics->getFont("DejaVu Sans", "normal");
    $size = 9;
    $pdf->page_text($pdf->get_width() - 120, $pdf->get_height() - 35, $text, $font, $size, [0.4,0.4,0.4]);
}
</script>

</body>

</html>