<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Jurnal Kegiatan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @media print {
            .no-print { display: none; }
            .agenda-container { page-break-inside: avoid; }
            img {
                max-width: 100% !important;
                display: block !important;
            }
        }
        body {
            font-size: 14px;
            font-family: "Times New Roman", Times, serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .agenda-title {
            text-align: center;
            font-weight: bold;
            margin: 15px 0;
        }
        .agenda-container {
            page-break-after: always;
            margin-bottom: 30px;
            padding-bottom: 20px;
            position: relative;
        }
        .agenda-container:last-child {
            page-break-after: auto;
        }
        .foto-wrapper {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-top: 10px;
            gap: 10px;
        }
        .foto-container {
            text-align: center;
            height: 200px;
            border: 1px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex: 1 1 45%;
            min-width: 45%;
            margin-bottom: 10px;
        }
        .foto-container img {
            max-width: 100%;
            max-height: 100%;
            height: auto;
            width: auto;
            display: block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
        }
        .photo-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .photo-col {
            flex: 0 0 48%;
            margin-bottom: 10px;
        }
        .signature-section {
            margin-top: 40px;
            width: 100%;
        }
        .signature-wrapper {
            display: flex;
            justify-content: center;
            gap: 100px;
            width: 100%;
            margin-top: 15px;
        }
        .signature-box {
            width: 45%;
            text-align: center;
            display: inline-block;
            vertical-align: top;
        }
        .signature-title {
            margin-bottom: 20px;
        }
        .signature-name {
            font-weight: bold;
            margin-top: 50px;
        }
        .knowing-text {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>JURNAL KEGIATAN</h1>
            <hr>
        </div>

        @if ($agendas->count())
            @foreach ($agendas as $index => $agenda)
                <div class="agenda-container">
                    <h3 class="agenda-title">AGENDA {{ $index + 1 }}</h3>

                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Kegiatan</th>
                                <th>Deskripsi</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($agenda->tanggal)->format('d M Y') }}</td>
                                <td>{{ $agenda->nama_kegiatan }}</td>
                                <td>{{ $agenda->deskripsi }}</td>
                                <td>{{ $agenda->keterangan }}</td>
                            </tr>
                            <tr>
                                <th colspan="4" style="text-align: center;">Foto Dokumentasi</th>
                            </tr>
                            <tr>
                                <td colspan="4" style="padding: 10px;">
                                    @if ($agenda->foto)
                                        @php
                                            $photos = json_decode($agenda->foto);
                                            $photoCount = count($photos);
                                        @endphp
                                        <div class="photo-row">
                                            @foreach($photos as $i => $photo)
                                                @php
                                                    $filePath = storage_path('app/public/uploads/'.$photo);
                                                @endphp
                                                @if(file_exists($filePath))
                                                    @php
                                                        $imageData = base64_encode(file_get_contents($filePath));
                                                        $src = 'data:image/'.pathinfo($filePath, PATHINFO_EXTENSION).';base64,'.$imageData;
                                                    @endphp
                                                    <div class="photo-col">
                                                        <div class="foto-container">
                                                            <img src="{{ $src }}" alt="Foto Kegiatan">
                                                        </div>
                                                    </div>
                                                    <!-- Jika foto ganjil dan terakhir, tambahkan div kosong untuk alignment -->
                                                    @if($photoCount % 2 != 0 && $i == $photoCount - 1)
                                                        <div class="photo-col" style="visibility: hidden;"></div>
                                                    @endif
                                                @else
                                                    <div class="photo-col">
                                                        <span class="text-danger">File tidak ditemukan: {{ $photo }}</span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted text-center">Tidak ada foto dokumentasi</p>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Tanda Tangan -->
                    <div class="signature-section">
                        <div class="signature-wrapper">
                            <div class="signature-box">
                                <p class="signature-title"><strong>Kepala Sekolah</strong></p>
                                <div class="signature-name">(__________________)</div>
                            </div>

                            <div class="signature-box">
                                <p class="signature-title"><strong>{{ $agenda->jabatan }}</strong></p>
                                <div class="signature-name">{{ $agenda->penanggung_jawab }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-warning text-center">Tidak ada data jurnal kegiatan yang tersedia.</div>

            <!-- Tanda Tangan Kosong -->
            <div class="signature-section">
                <div class="signature-wrapper">
                    <div class="signature-box">
                        <p class="signature-title">Kepala Sekolah</p>
                        <div class="signature-name">__________________</div>
                    </div>
                    <div class="signature-box">
                        <p class="signature-title">Manager Pemasaran</p>
                        <div class="signature-name">__________________</div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</body>
</html>