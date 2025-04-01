<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Output Agenda</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-3">Output Agenda</h1>

        @if ($agendas->count())
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Kegiatan</th>
                        <th>Tanggal</th>
                        <th>Deskripsi</th>
                        <th>Keterangan</th> <!-- ✅ Tambahkan Keterangan -->
                        <th>Foto</th>
                        <th>Penanggung Jawab</th> <!-- ✅ Pastikan nama benar -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($agendas as $index => $agenda)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $agenda->nama_kegiatan }}</td>
                        <td>{{ \Carbon\Carbon::parse($agenda->tanggal)->format('d M Y') }}</td>
                        <td>{{ $agenda->deskripsi }}</td>
                        <td>{{ $agenda->keterangan }}</td> <!-- ✅ Menampilkan Keterangan -->
                        <td>
                            @if ($agenda->foto && Storage::exists('public/uploads/' . $agenda->foto))
                                <img src="{{ asset('storage/uploads/' . $agenda->foto) }}" alt="Foto" width="100" class="img-thumbnail">
                            @else
                                <span class="text-muted">Tidak ada foto</span>
                            @endif
                        </td>
                        <td>{{ $agenda->penanggung_jawab }}</td> <!-- ✅ Perbaikan nama kolom -->
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <a href="{{ route('agenda.print') }}" class="btn btn-success">Cetak PDF</a> <!-- ✅ Pastikan rute benar -->
        @else
            <div class="alert alert-warning">Tidak ada data agenda yang tersedia.</div>
        @endif

        <a href="{{ route('agenda.index') }}" class="btn btn-secondary">Kembali</a> <!-- ✅ Pastikan rute benar -->
    </div>
</body>
</html>
