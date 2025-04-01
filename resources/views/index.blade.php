<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Agenda</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-3">Daftar Agenda</h1>

        <!-- Pesan sukses -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form Pencarian -->
        <form action="{{ route('agenda.index') }}" method="GET" class="mb-3">
            <div class="input-group" style="max-width: 400px;">
                <input type="text" name="search" placeholder="Cari agenda..." class="form-control">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </div>
        </form>

        <a href="{{ route('agenda.create') }}" class="btn btn-success mb-3">Tambah Agenda</a>

        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Kegiatan</th>
                    <th>Tanggal</th>
                    <th>Deskripsi</th>
                    <th>Keterangan</th> <!-- ✅ Tambahkan kolom Keterangan -->
                    <th>Foto</th>
                    <th>Penanggung Jawab</th> <!-- ✅ Pastikan ini benar -->
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($agendas as $index => $agenda)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $agenda->nama_kegiatan }}</td>
                        <td>{{ $agenda->tanggal }}</td>
                        <td>{{ $agenda->deskripsi }}</td>
                        <td>{{ $agenda->keterangan }}</td> <!-- ✅ Menampilkan Keterangan -->
                        <td>
                            @if ($agenda->foto)
                            <img src="{{ asset('storage/' . $agenda->foto) }}" alt="Foto" width="100" class="img-thumbnail">
                            @else
                                <span class="text-muted">Tidak ada foto</span>
                            @endif
                        </td>
                        </td>
                        <td>{{ $agenda->penanggung_jawab }}</td>
                        <td>
                            <!-- Tombol Edit -->
                            <a href="{{ route('agenda.edit', $agenda->id) }}" class="btn btn-warning btn-sm">Edit</a>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('agenda.destroy', $agenda->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus agenda ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('agenda.print') }}" class="btn btn-info mb-3 no-print">Print Agenda</a>


        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $agendas->links() }}
        </div>
    </div>
</body>
</html>
