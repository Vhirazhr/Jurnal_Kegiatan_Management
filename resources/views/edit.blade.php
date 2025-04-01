<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Agenda</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-3">Edit Agenda</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('agenda.update', $agenda->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nama_kegiatan">Nama Kegiatan:</label>
                <input type="text" name="nama_kegiatan" class="form-control" value="{{ old('nama_kegiatan', $agenda->nama_kegiatan) }}" required>
            </div>

            <div class="form-group">
                <label for="tanggal">Hari/Tanggal:</label>
                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $agenda->tanggal) }}" required>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi:</label>
                <textarea name="deskripsi" class="form-control" required>{{ old('deskripsi', $agenda->deskripsi) }}</textarea>
            </div>

            <div class="form-group">
                <label for="keterangan">Keterangan:</label>
                <textarea name="keterangan" class="form-control" required>{{ old('keterangan', $agenda->keterangan) }}</textarea>
            </div>

            <div class="form-group">
                <label for="foto">Foto:</label>
                <input type="file" name="foto" class="form-control">
                @if ($agenda->foto)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $agenda->foto) }}" alt="Foto" width="150" class="img-thumbnail">
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="penanggung_jawab">Penanggung Jawab:</label>
                <input type="text" name="penanggung_jawab" class="form-control" value="{{ old('penanggung_jawab', $agenda->penanggung_jawab) }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Perbarui Agenda</button>
            <a href="{{ route('agenda.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>
