<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #1a1a1a; }

        /* ============================================================
           MARGIN HALAMAN
           Meniru margin dokumen kantor (mis. Word): bagian atas sengaja
           dikosongkan untuk tempat kop surat/letterhead, bagian bawah
           dikosongkan untuk tempat QR code pojok kiri bawah.
           Ubah nilai di sini kalau ukuran kop surat berbeda.
        ============================================================ */
        @page {
            margin: 3.5cm 2cm 3.2cm 2cm; /* atas kanan bawah kiri */
        }

        .content-wrapper {
            padding: 0 10px;
        }

        .permit-title {
            text-align: center;
            margin-bottom: 25px;
        }

        .permit-title h1 {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .permit-number {
            font-size: 13px;
            font-weight: bold;
            margin-top: 4px;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .detail-table td {
            padding: 5px 8px;
            vertical-align: top;
            font-size: 11px;
            line-height: 1.5;
        }

        .detail-table .label {
            width: 160px;
            font-weight: bold;
            color: #333;
        }

        .detail-table .separator {
            width: 15px;
            color: #999;
        }

        .detail-table .value {
            color: #1a1a1a;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1px solid #ccc;
            padding-bottom: 4px;
            margin: 18px 0 10px 0;
            color: #333;
        }

        .verify-info {
            margin-top: 25px;
            padding: 10px;
            border: 1px dashed #ccc;
            text-align: center;
        }

        .verify-info .token {
            font-size: 8px;
            color: #888;
            word-break: break-all;
        }

        .verify-info .url {
            font-size: 9px;
            color: #555;
            margin-top: 3px;
        }

        /* ============================================================
           QR CODE — POJOK KIRI BAWAH
           position: fixed membuat elemen ini menempel di posisi yang
           sama pada setiap halaman (dompdf memperlakukan fixed mirip
           header/footer berulang). Karena surat ini biasanya 1 halaman,
           efeknya QR akan selalu nangkring di pojok kiri bawah.

           Kalau mau geser posisi atau ukuran QR, cukup ubah 4 nilai
           di bawah ini (left, bottom, width/height gambar).
        ============================================================ */
        .qr-corner {
            position: fixed;
            left: 0;
            bottom: -60px;   /* jarak dari tepi bawah margin @page */
            width: 110px;
            text-align: center;
        }

        .qr-corner img.qr-code {
            width: 90px;
            height: 90px;
        }

        .qr-corner .qr-label {
            font-size: 8px;
            color: #666;
            margin-top: 3px;
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <div class="permit-title">
            <h1>Surat Izin Peminjaman Ruangan</h1>
            <div class="permit-number">No. {{ $permit->permit_number }}</div>
        </div>

        <div class="section-title">Data Kegiatan</div>
        <table class="detail-table">
            <tr>
                <td class="label">Nama Kegiatan</td>
                <td class="separator">:</td>
                <td class="value">{{ $booking->activity_name }}</td>
            </tr>
            @if($booking->purpose)
            <tr>
                <td class="label">Tujuan Kegiatan</td>
                <td class="separator">:</td>
                <td class="value">{{ $booking->purpose }}</td>
            </tr>
            @endif
            <tr>
                <td class="label">Organisasi</td>
                <td class="separator">:</td>
                <td class="value">{{ $booking->organization->name ?? '-' }}</td>
            </tr>
        </table>

        <div class="section-title">Jadwal Peminjaman</div>
        <table class="detail-table">
            <tr>
                <td class="label">Ruangan</td>
                <td class="separator">:</td>
                <td class="value">{{ $booking->room->name }} ({{ $booking->room->code }})</td>
            </tr>
            <tr>
                <td class="label">Tanggal</td>
                <td class="separator">:</td>
                <td class="value">{{ $booking->booking_date->locale('id')->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Waktu</td>
                <td class="separator">:</td>
                <td class="value">{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB</td>
            </tr>
            @php
                $startH = (int) substr($booking->start_time, 0, 2);
                $startM = (int) substr($booking->start_time, 3, 2);
                $endH = (int) substr($booking->end_time, 0, 2);
                $endM = (int) substr($booking->end_time, 3, 2);
                $totalMinutes = ($endH * 60 + $endM) - ($startH * 60 + $startM);
                $hours = intdiv($totalMinutes, 60);
                $minutes = $totalMinutes % 60;
                $durasi = $hours > 0 ? ($hours . ' Jam' . ($minutes > 0 ? ' ' . $minutes . ' Menit' : '')) : ($minutes . ' Menit');
            @endphp
            <tr>
                <td class="label">Durasi</td>
                <td class="separator">:</td>
                <td class="value">{{ $durasi }}</td>
            </tr>
        </table>

        <div class="section-title">Peserta & Penanggung Jawab</div>
        <table class="detail-table">
            <tr>
                <td class="label">Jumlah Peserta</td>
                <td class="separator">:</td>
                <td class="value">{{ $booking->participant_count }} orang</td>
            </tr>
            <tr>
                <td class="label">Penanggung Jawab</td>
                <td class="separator">:</td>
                <td class="value">{{ $booking->person_in_charge }}</td>
            </tr>
            <tr>
                <td class="label">No. Telepon / WhatsApp</td>
                <td class="separator">:</td>
                <td class="value">{{ $booking->contact_phone }}</td>
            </tr>
        </table>

        <div class="section-title">Informasi Izin</div>
        <table class="detail-table">
            <tr>
                <td class="label">Nomor Izin</td>
                <td class="separator">:</td>
                <td class="value">{{ $permit->permit_number }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Diterbitkan</td>
                <td class="separator">:</td>
                <td class="value">{{ $permit->issued_at->locale('id')->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Status</td>
                <td class="separator">:</td>
                <td class="value" style="text-transform: uppercase; font-weight: bold;">{{ $permit->status }}</td>
            </tr>
        </table>

        <div class="verify-info">
            <div class="url">{{ $verifyUrl }}</div>
            <div class="token">Token: {{ $permit->verification_token }}</div>
        </div>
    </div>

    <!-- QR Code — pojok kiri bawah, lihat blok .qr-corner di <style> untuk atur posisi -->
    <div class="qr-corner">
        <img class="qr-code" src="data:image/png;base64,{{ $qrBase64 }}" alt="QR Code Verifikasi">
        <div class="qr-label">Scan untuk verifikasi</div>
    </div>
</body>
</html>