<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Agenda</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-3">Formulir Agenda</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('agenda.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="agenda-forms">
                <div class="agenda-form border p-3 mb-3">
                    <h3>Agenda 1</h3>
                    <div class="form-group">
                        <label>Nama Kegiatan:</label>
                        <input type="text" name="nama_kegiatan[]" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Hari/Tanggal:</label>
                        <input type="date" name="tanggal[]" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi:</label>
                        <textarea name="deskripsi[]" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Keterangan:</label>
                        <textarea name="keterangan[]" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Foto:</label>
                        <input type="file" name="foto[]" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Penanggung Jawab:</label>
                        <input type="text" name="penanggung_jawab[]" class="form-control" required>
                    </div>
                </div>
            </div>

            <button type="button" id="add-agenda" class="btn btn-secondary">Tambah Agenda</button>
            <button type="submit" class="btn btn-primary">Simpan Agenda</button>
        </form>
    </div>

    <script>
        document.getElementById('add-agenda').addEventListener('click', function() {
            const agendaForms = document.getElementById('agenda-forms');
            const agendaCount = agendaForms.children.length + 1;

            const newAgendaForm = document.createElement('div');
            newAgendaForm.classList.add('agenda-form', 'border', 'p-3', 'mb-3');
            newAgendaForm.innerHTML = `
                <h3>Agenda ${agendaCount}</h3>
                <div class="form-group">
                    <label>Nama Kegiatan:</label>
                    <input type="text" name="nama_kegiatan[]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Hari/Tanggal:</label>
                    <input type="date" name="tanggal[]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi:</label>
                    <textarea name="deskripsi[]" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <label>Keterangan:</label>
                    <textarea name="keterangan[]" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <label>Foto:</label>
                    <input type="file" name="foto[]" class="form-control">
                </div>
                <div class="form-group">
                    <label>Penanggung Jawab:</label>
                    <input type="text" name="penanggung_jawab[]" class="form-control" required>
                </div>
                <button type="button" class="btn btn-danger btn-sm remove-agenda">Hapus</button>
            `;

            agendaForms.appendChild(newAgendaForm);

            // Event listener untuk tombol hapus agenda
            newAgendaForm.querySelector('.remove-agenda').addEventListener('click', function() {
                newAgendaForm.remove();
            });
        });
    </script>
</body>
</html>
