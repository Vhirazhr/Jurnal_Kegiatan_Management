<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Agenda</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .img-thumbnail {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
        }
        .action-buttons {
            white-space: nowrap;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .photo-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
            align-items: center;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            .table {
                font-size: 12px;
            }
            .action-buttons {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-3">Daftar Agenda</h1>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row mb-3">
            <div class="col-md-6 d-flex align-items-center">
                <a href="{{ route('welcome') }}" class="btn btn-secondary mr-2">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <form action="{{ route('agenda.index') }}" method="GET" class="form-inline">
                    <div class="input-group">
                        <input type="text" name="search" placeholder="Cari agenda..." class="form-control" value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('agenda.create') }}" class="btn btn-success mr-2">
                    <i class="fas fa-plus"></i> Tambah Agenda
                </a>
                <a href="{{ route('agenda.print') }}" class="btn btn-info no-print">
                    <i class="fas fa-print"></i> Cetak
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Kegiatan</th>
                        <th width="10%">Tanggal</th>
                        <th>Deskripsi</th>
                        <th>Keterangan</th>
                        <th width="15%">Foto</th>
                        <th>Penanggung Jawab</th>
                        <th>Jabatan</th>
                        <th width="12%" class="no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($agendas as $index => $agenda)
                        <tr>
                            <td>{{ ($agendas->currentPage() - 1) * $agendas->perPage() + $loop->iteration }}</td>
                            <td>{{ $agenda->nama_kegiatan }}</td>
                            <td>{{ \Carbon\Carbon::parse($agenda->tanggal)->format('d/m/Y') }}</td>
                            <td>{{ Str::limit($agenda->deskripsi, 50) }}</td>
                            <td>{{ Str::limit($agenda->keterangan, 50) }}</td>
                            <td class="text-center">
                                @if ($agenda->foto)
                                    @php $fotos = json_decode($agenda->foto); @endphp
                                    <div class="photo-container">
                                        @foreach($fotos as $foto)
                                            @if(Storage::exists('public/uploads/'.basename($foto)))
                                                <img src="{{ asset('storage/uploads/'.basename($foto)) }}" 
                                                     alt="Foto Kegiatan" 
                                                     class="img-thumbnail"
                                                     onerror="this.onerror=null;this.src='{{ asset('images/default-image.jpg') }}';">
                                            @else
                                                <span class="text-danger">File tidak ditemukan</span>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $agenda->penanggung_jawab }}</td>
                            <td>{{ $agenda->jabatan }}</td>
                            <td class="action-buttons no-print">
                                <a href="{{ route('agenda.edit', $agenda->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('agenda.destroy', $agenda->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus agenda ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data agenda</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted">
                Menampilkan {{ $agendas->firstItem() }} sampai {{ $agendas->lastItem() }} dari {{ $agendas->total() }} entri
            </div>
            <div>
                {{ $agendas->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
