<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Formulir Agenda</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>
  <style>
    .agenda-form {
      background-color: #f8f9fa;
      border-radius: 5px;
      margin-bottom: 20px;
      padding: 20px;
      border: 1px solid #dee2e6;
    }
    .other-jabatan-container {
      display: none;
      margin-top: 10px;
    }
    .photo-input-container {
      margin-bottom: 15px;
    }
    .custom-file-label::after {
      content: "Browse";
    }
    .btn-add-item {
      margin-top: 5px;
    }
    .remove-btn {
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="container py-4">
    <h1 class="mb-4 text-center">Formulir Agenda</h1>

    <form action="{{ route('agenda.store') }}" method="POST" enctype="multipart/form-data" id="agendaForm">
      @csrf
      <div id="agenda-forms"></div>

      <div class="d-flex justify-content-between mt-4">
        <button type="button" id="add-agenda" class="btn btn-outline-primary">
          <i class="fas fa-plus"></i> Tambah Agenda
        </button>
        <div>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan Semua Agenda
          </button>
        </div>
      </div>
    </form>
  </div>

  <!-- Template agenda -->
  <div id="agenda-template" style="display:none;">
    <div class="agenda-form">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="agenda-title">Agenda 1</h3>
        <button type="button" class="btn btn-sm btn-danger remove-agenda">
          <i class="fas fa-trash"></i> Hapus Agenda
        </button>
      </div>

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
        <textarea name="deskripsi[]" class="form-control" rows="3" required></textarea>
      </div>

      <div class="form-group">
        <label>Keterangan:</label>
        <textarea name="keterangan[]" class="form-control" rows="3" required></textarea>
      </div>

      <div class="form-group">
        <label>Foto:</label>
        <div class="photo-inputs">
          <div class="photo-input-container">
            <div class="input-group">
              <div class="custom-file">
                <input type="file" class="custom-file-input photo-input" name="foto[0][]" accept="image/*">
                <label class="custom-file-label">Pilih file</label>
              </div>
              <div class="input-group-append">
                <button class="btn btn-outline-danger remove-photo" type="button">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary btn-add-photo">
          <i class="fas fa-plus"></i> Tambah Foto
        </button>
      </div>

      <div class="form-group">
        <label>Penanggung Jawab:</label>
        <input type="text" name="penanggung_jawab[]" class="form-control" required>
      </div>

      <div class="form-group">
        <label>Jabatan:</label>
        <select name="jabatan[]" class="form-control jabatan-select" required>
          <option value="Waka Humas">Waka Humas</option>
          <option value="SARPRAS">SARPRAS</option>
          <option value="Kurikulum">Kurikulum</option>
          <option value="Kesiswaan">Kesiswaan</option>
          <option value="Lainnya">Lainnya</option>
        </select>
        <div class="other-jabatan-container mt-2">
          <input type="text" name="other_jabatan[0]" class="form-control" placeholder="Masukkan jabatan lainnya">
        </div>
      </div>
    </div>
  </div>

  <!-- JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

  <script>
    $(document).ready(function () {
      addNewAgenda();

      function addNewAgenda() {
        const agendaCount = $('#agenda-forms .agenda-form').length;
        const newAgenda = $('#agenda-template').html().replace(/\[0\]/g, `[${agendaCount}]`);
        $('#agenda-forms').append(newAgenda);
        updateAgendaTitles();
      }

      function updateAgendaTitles() {
        $('#agenda-forms .agenda-form').each(function (index) {
          $(this).find('.agenda-title').text(`Agenda ${index + 1}`);
          $(this).find('[name]').each(function () {
            let name = $(this).attr('name');
            name = name.replace(/\[\d+\]/g, `[${index}]`);
            $(this).attr('name', name);
          });
        });
      }

      $('#add-agenda').click(function () {
        addNewAgenda();
      });

      $(document).on('click', '.remove-agenda', function () {
        if ($('#agenda-forms .agenda-form').length > 1) {
          $(this).closest('.agenda-form').remove();
          updateAgendaTitles();
        } else {
          alert('Minimal harus ada satu agenda!');
        }
      });

      $(document).on('change', '.jabatan-select', function () {
        const container = $(this).closest('.form-group').find('.other-jabatan-container');
        if ($(this).val() === 'Lainnya') {
          container.show();
          container.find('input').prop('required', true);
        } else {
          container.hide();
          container.find('input').prop('required', false).val('');
        }
      });

      $(document).on('click', '.btn-add-photo', function () {
        const agendaIndex = $(this).closest('.agenda-form').index();
        const photoContainer = $(this).closest('.form-group').find('.photo-inputs');

        const newInput = `
          <div class="photo-input-container">
            <div class="input-group">
              <div class="custom-file">
                <input type="file" class="custom-file-input photo-input" name="foto[${agendaIndex}][]" accept="image/*">
                <label class="custom-file-label">Pilih file</label>
              </div>
              <div class="input-group-append">
                <button class="btn btn-outline-danger remove-photo" type="button">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </div>`;
        photoContainer.append(newInput);
      });

      $(document).on('click', '.remove-photo', function () {
        const parent = $(this).closest('.form-group');
        if (parent.find('.photo-input-container').length > 1) {
          $(this).closest('.photo-input-container').remove();
        } else {
          alert('Minimal harus ada satu foto!');
        }
      });

      $(document).on('change', '.photo-input', function () {
        const fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName || 'Pilih file');
      });

      $('#agendaForm').on('submit', function (e) {
        let isValid = true;

        $('.jabatan-select').each(function () {
          if ($(this).val() === 'Lainnya') {
            const otherInput = $(this).closest('.form-group').find('.other-jabatan-container input');
            if (!otherInput.val()) {
              alert('Harap isi jabatan lainnya!');
              otherInput.focus();
              isValid = false;
              return false;
            }
          }
        });

        if (!isValid) e.preventDefault();
      });
    });
  </script>
</body>
</html>
