<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Agenda</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .photo-preview {
            max-width: 150px;
            max-height: 150px;
            margin-right: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            padding: 5px;
        }
        .photo-container {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }
        .photo-item {
            position: relative;
            margin-right: 15px;
            margin-bottom: 15px;
        }
        .remove-photo-btn {
            position: absolute;
            top: -10px;
            right: -10px;
            background: red;
            color: white;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            border: none;
            font-size: 12px;
            cursor: pointer;
        }
        .other-jabatan-container {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <h1 class="mb-4">Edit Agenda</h1>

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

            <div class="card mb-4">
                <div class="card-body">
                    <div class="form-group">
                        <label>Nama Kegiatan:</label>
                        <input type="text" name="nama_kegiatan" class="form-control" value="{{ old('nama_kegiatan', $agenda->nama_kegiatan) }}" required>
                    </div>

            <div class="form-group">
                <label>Hari/Tanggal:</label>
                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $agenda->tanggal) }}" required>
            </div>

            <div class="form-group">
                <label>Deskripsi:</label>
                <textarea name="deskripsi" class="form-control" rows="3" required>{{ old('deskripsi', $agenda->deskripsi) }}</textarea>
            </div>

            <div class="form-group">
                <label>Keterangan:</label>
                <textarea name="keterangan" class="form-control" rows="3" required>{{ old('keterangan', $agenda->keterangan) }}</textarea>
            </div>

            <div class="form-group">
                <label>Foto Saat Ini:</label>
                <div class="photo-container">
                    @foreach(json_decode($agenda->foto) as $index => $foto)
                        <div class="photo-item">
                            <img src="{{ asset('storage/uploads/'.$foto) }}" class="photo-preview" alt="Foto {{ $index+1 }}">
                            <button type="button" class="remove-photo-btn" data-filename="{{ $foto }}">×</button>
                            <input type="hidden" name="existing_photos[]" value="{{ $foto }}">
                        </div>
                    @endforeach
                </div>
                
                <label>Tambah Foto Baru:</label>
                <div class="photo-inputs">
                    <div class="input-group mb-2">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="new_photos[]" multiple>
                            <label class="custom-file-label">Pilih file</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Penanggung Jawab:</label>
                <input type="text" name="penanggung_jawab" class="form-control" value="{{ old('penanggung_jawab', $agenda->penanggung_jawab) }}" required>
            </div>

            <div class="form-group">
                <label>Jabatan:</label>
                <select name="jabatan" class="form-control jabatan-select" required>
                    <option value="Waka Humas" {{ $agenda->jabatan == 'Waka Humas' ? 'selected' : '' }}>Waka Humas</option>
                    <option value="SARPRAS" {{ $agenda->jabatan == 'SARPRAS' ? 'selected' : '' }}>SARPRAS</option>
                    <option value="Kurikulum" {{ $agenda->jabatan == 'Kurikulum' ? 'selected' : '' }}>Kurikulum</option>
                    <option value="Kesiswaan" {{ $agenda->jabatan == 'Kesiswaan' ? 'selected' : '' }}>Kesiswaan</option>
                    <option value="Lainnya" {{ !in_array($agenda->jabatan, ['Waka Humas', 'SARPRAS', 'Kurikulum', 'Kesiswaan']) ? 'selected' : '' }}>Lainnya</option>
                </select>
                <div class="other-jabatan-container mt-2" style="{{ !in_array($agenda->jabatan, ['Waka Humas', 'SARPRAS', 'Kurikulum', 'Kesiswaan']) ? 'display:block;' : 'display:none;' }}">
                    <input type="text" name="other_jabatan" class="form-control" placeholder="Masukkan jabatan lainnya" value="{{ !in_array($agenda->jabatan, ['Waka Humas', 'SARPRAS', 'Kurikulum', 'Kesiswaan']) ? $agenda->jabatan : '' }}">
                </div>
            </div>
        </div>
    </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('agenda.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Perbarui Agenda</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <script>
    $(document).ready(function() {
        // Toggle kolom jabatan lainnya
        $('.jabatan-select').change(function() {
            const container = $(this).closest('.form-group').find('.other-jabatan-container');
            if ($(this).val() === 'Lainnya') {
                container.show();
                container.find('input').prop('required', true);
            } else {
                container.hide();
                container.find('input').prop('required', false).val('');
            }
        });

        // Hapus foto yang ada
        $(document).on('click', '.remove-photo-btn', function() {
            const filename = $(this).data('filename');
            $(this).closest('.photo-item').remove();
            $('<input>').attr({
                type: 'hidden',
                name: 'removed_photos[]',
                value: filename
            }).appendTo('form');
        });

        // Tampilkan nama file yang dipilih
        $(document).on('change', '.custom-file-input', function() {
            const files = $(this)[0].files;
            let label = 'Pilih file';
            
            if (files.length > 1) {
                label = files.length + ' file dipilih';
            } else if (files.length === 1) {
                label = files[0].name;
            }
            
            $(this).next('.custom-file-label').text(label);
        });
    });
    </script>
</body>
</html>