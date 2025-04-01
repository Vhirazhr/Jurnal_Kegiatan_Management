<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Jurnal Kegiatan</title> {{-- Judul title diubah --}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @media print {
            .no-print { display: none; }
            .agenda-container { page-break-inside: avoid; }
        }
        body {
            font-size: 14px;
            font-family: Arial, sans-serif;
        }
        .agenda-container {
            page-break-after: always;
            margin-bottom: 30px;
            padding-bottom: 100px;
            position: relative;
            min-height: 80vh;
        }
        .agenda-container:last-child {
            page-break-after: auto;
        }
        .foto-container {
            text-align: center;
            margin-top: 10px;
            height: 200px;
            border: 1px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
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
        .signature-section {
            margin-top: 40px;
            width: 100%;
        }
        .signature-box {
            width: 45%; /* Sedikit penyesuaian lebar jika perlu */
            text-align: center;
            margin-top: 20px;
            display: inline-block; /* Agar bisa berdampingan */
            vertical-align: top; /* Agar sejajar atas */
        }
         /* Menggunakan flexbox untuk layout TTD yang lebih mudah */
        .signature-wrapper {
            display: flex;
            justify-content: space-around; /* Atau space-between */
            width: 100%;
            margin-top: 15px; /* Jarak dari "Mengetahui" */
        }
        .signature-line {
            margin-top: 80px !important; /* Jarak vertikal TTD */
            border-bottom: 1px solid black;
            width: 70%; /* Lebar garis TTD */
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        {{-- Judul Utama Diubah --}}
        <h1 class="text-center">Jurnal Kegiatan</h1>
        <hr>

        @if ($agendas->count())
            @foreach ($agendas as $index => $agenda)
                <div class="agenda-container">
                    {{-- Tidak perlu lagi "Agenda X" jika ini jurnal, bisa dihapus atau disesuaikan --}}
                    {{-- <h4>Agenda {{ $index + 1 }}</h4> --}}
                    <table>
                        <thead>
                            <tr>
                                {{-- Kolom Tanggal dipindah ke depan --}}
                                <th>Tanggal</th>
                                <th>Nama Kegiatan</th>
                                <th>Deskripsi</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                {{-- Data Tanggal dipindah ke depan --}}
                                <td>{{ \Carbon\Carbon::parse($agenda->tanggal)->format('d M Y') }}</td>
                                <td>{{ $agenda->nama_kegiatan }}</td>
                                <td>{{ $agenda->deskripsi }}</td>
                                <td>{{ $agenda->keterangan }}</td>
                            </tr>
                            <tr>
                                <th colspan="4" style="text-align: center;">Foto</th>
                            </tr>
                            <tr>
                                <td colspan="4" style="padding: 0;">
                                    <div class="foto-container">
                                        @if ($agenda->foto)
                                        <img src="{{ asset('storage/' . $agenda->foto) }}" alt="Foto Kegiatan">
                                        @else
                                        <p class="text-muted mb-0">Tidak ada foto</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- BAGIAN TANDA TANGAN --}}
                    <div class="signature-section">
                        <p class="text-center">Mengetahui</p>
                        <div class="signature-wrapper">
                             {{-- Kepala Sekolah di Kiri --}}
                             <div class="signature-box">
                                 <p><strong>Kepala Sekolah</strong></p>
                                 <div class="signature-line"></div>
                                 {{-- Tempat nama Kepala Sekolah --}}
                                 {{-- <p style="margin-top: 5px;">(Nama Kepala Sekolah)</p> --}}
                             </div>
                             {{-- Penanggung Jawab di Kanan --}}
                             <div class="signature-box">
                                 <p><strong>Penanggung Jawab</strong></p>
                                 <div class="signature-line"></div>
                                  {{-- Tempat nama Penanggung Jawab --}}
                                 {{-- <p style="margin-top: 5px;">(Nama Penanggung Jawab)</p> --}}
                             </div>
                        </div>
                        {{-- <div style="clear: both;"></div> --}} {{-- Tidak perlu clear: both jika pakai flexbox --}}
                    </div>
                    {{-- AKHIR BAGIAN TANDA TANGAN --}}

                </div> {{-- Akhir .agenda-container --}}
            @endforeach
        @else
            <div class="alert alert-warning text-center">Tidak ada data jurnal kegiatan yang tersedia.</div>
            {{-- Tampilkan TTD Kosong jika tidak ada data? (Opsional) --}}
             <div class="signature-section" style="margin-top: 50px;">
                 <p class="text-center">Mengetahui</p>
                 <div class="signature-wrapper">
                      <div class="signature-box">
                          <p><strong>Kepala Sekolah</strong></p>
                          <div class="signature-line"></div>
                      </div>
                      <div class="signature-box">
                          <p><strong>Penanggung Jawab</strong></p>
                          <div class="signature-line"></div>
                      </div>
                 </div>
             </div>
        @endif

    </div>

</body>
</html>