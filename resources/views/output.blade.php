<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Agenda Kegiatan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .header p {
            margin-bottom: 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: top;
        }
        .table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .photo-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
            align-items: center;
        }
        .photo-item {
            text-align: center;
            margin-bottom: 10px;
        }
        .photo-item img {
            max-width: 150px;
            max-height: 150px;
            border: 1px solid #ddd;
            padding: 3px;
        }
        .signature-section {
            margin-top: 50px;
            width: 100%;
        }
        .signature-wrapper {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }
        .signature-box {
            width: 45%;
            text-align: center;
        }
        .signature-line {
            margin-top: 60px;
            border-bottom: 1px solid #000;
            width: 70%;
            margin-left: auto;
            margin-right: auto;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
                font-size: 11px;
            }
            .table {
                page-break-inside: auto;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            .header {
                margin-top: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>LAPORAN KEGIATAN SEKOLAH</h1>
            <p>Periode: {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
        </div>

        @if ($agendas->count())
            <table class="table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Nama Kegiatan</th>
                        <th width="10%">Tanggal</th>
                        <th width="20%">Deskripsi</th>
                        <th width="15%">Keterangan</th>
                        <th width="15%">Dokumentasi</th>
                        <th width="10%">Penanggung Jawab</th>
                        <th width="10%">Jabatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($agendas as $index => $agenda)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $agenda->nama_kegiatan }}</td>
                        <td>{{ \Carbon\Carbon::parse($agenda->tanggal)->format('d/m/Y') }}</td>
                        <td>{{ $agenda->deskripsi }}</td>
                        <td>{{ $agenda->keterangan }}</td>
                        <td>
                            @if ($agenda->foto)
                                @php
                                    $fotos = json_decode($agenda->foto);
                                @endphp
                                <div class="photo-container">
                                    @foreach($fotos as $foto)
                                        @php
                                            $storagePath = 'public/uploads/'.basename($foto);
                                            $publicPath = 'uploads/'.basename($foto);
                                        @endphp
                                        <div class="photo-item">
                                            @if(Storage::exists($storagePath) || file_exists(public_path($publicPath))
                                                <img src="{{ Storage::exists($storagePath) ? asset('storage/uploads/'.basename($foto)) : asset($publicPath) }}" 
                                                     alt="Dokumentasi Kegiatan"
                                                     onerror="this.onerror=null;this.src='{{ asset('images/default-image.jpg') }}';">
                                            @else
                                                <span class="text-danger">File tidak ditemukan</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $agenda->penanggung_jawab }}</td>
                        <td>{{ $agenda->jabatan }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="signature-section">
                <div class="signature-wrapper">
                    <div class="signature-box">
                        <p>Mengetahui,</p>
                        <p>Kepala Sekolah</p>
                        <div class="signature-line"></div>
                        <p>(.......................................)</p>
                    </div>
                    <div class="signature-box">
                        <p>{{ \Carbon\Carbon::now()->format('d F Y') }}</p>
                        <p>Koordinator Kegiatan</p>
                        <div class="signature-line"></div>
                        <p>(.......................................)</p>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-warning text-center">
                Tidak ada data agenda yang tersedia.
            </div>
        @endif

        <div class="text-center no-print mt-4">
            <button onclick="window.print()" class="btn btn-success mr-2">
                <i class="fas fa-print"></i> Cetak Laporan
            </button>
            <a href="{{ route('agenda.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <script>
        function printDocument() {
            window.print();
        }
    </script>
</body>
</html>