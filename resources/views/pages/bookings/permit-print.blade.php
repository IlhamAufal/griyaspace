<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        @page {
            margin: 0px;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #1a1a1a;
            padding-top: 4.1cm;
            padding-left: 1.5cm;
            padding-right: 1.5cm;
            padding-bottom: 3.5cm;
        }

        .content-container {
            width: 80%;
            margin: 0 auto;
        }

        .permit-title {
            text-align: center;
            margin-bottom: 15px;
        }

        .permit-title h1 {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #1a1a1a;
        }

        .permit-number {
            font-size: 11px;
            font-weight: bold;
            margin-top: 3px;
            color: #333;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1.5px solid #2F3185;
            padding-bottom: 3px;
            margin: 14px 0 6px 0;
            color: #2F3185;
            width: 100%;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .detail-table td {
            padding: 3px 4px;
            vertical-align: top;
            font-size: 11px;
            line-height: 1.45;
        }

        .detail-table .label {
            width: 165px;
            font-weight: bold;
            color: #333;
        }

        .detail-table .separator {
            width: 15px;
            text-align: center;
            color: #333;
        }

        .detail-table .value {
            color: #1a1a1a;
        }

        .qr-corner {
            position: fixed;
            left: 2.0cm;
            bottom: 2.0cm;
            width: 150px;
            text-align: center;
        }

        .qr-corner img.qr-code {
            width: 150px;
            height: 150px;
            display: block;
        }

        .qr-corner .qr-label {
            font-size: 14px;
            color: #666;
            margin-top: 2px;
        }
    </style>
</head>
<body>

    <div class="content-container">
        <!-- ===== JUDUL ===== -->
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

        <div class="section-title">Peserta &amp; Penanggung Jawab</div>
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

        {{-- <div class="section-title">Informasi Izin</div>
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
        </table> --}}
    </div>

    <!-- ===== QR CODE — pojok kiri bawah ===== -->
    <div class="qr-corner">
        <img class="qr-code" src="data:image/png;base64,{{ $qrBase64 }}" alt="QR Code Verifikasi">
        <div class="qr-label">Scan untuk verifikasi</div>
    </div>

</body>
</html>