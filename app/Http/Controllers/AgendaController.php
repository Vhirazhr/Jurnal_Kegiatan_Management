<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AgendaController extends Controller
{
    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|array',
            'nama_kegiatan.*' => 'required|string',
            'tanggal' => 'required|array',
            'tanggal.*' => 'required|date',
            'deskripsi' => 'required|array',
            'deskripsi.*' => 'required|string',
            'keterangan' => 'required|array',
            'keterangan.*' => 'required|string',
            'jabatan' => 'required|array',
            'jabatan.*' => 'required|string',
            'other_jabatan' => 'sometimes|array',
            'other_jabatan.*' => 'nullable|string|max:255',
            'foto' => 'sometimes|array',
            'foto.*' => 'sometimes|array',
            'foto.*.*' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'penanggung_jawab' => 'required|array',
            'penanggung_jawab.*' => 'required|string',
        ]);

        $totalData = min(
            count($request->nama_kegiatan),
            count($request->tanggal),
            count($request->deskripsi),
            count($request->keterangan),
            count($request->penanggung_jawab),
            count($request->jabatan)
        );

        for ($i = 0; $i < $totalData; $i++) {
            $agenda = new Agenda();
            $agenda->nama_kegiatan = $request->nama_kegiatan[$i];
            $agenda->tanggal = $request->tanggal[$i];
            $agenda->deskripsi = $request->deskripsi[$i];
            $agenda->keterangan = $request->keterangan[$i];
            $agenda->penanggung_jawab = $request->penanggung_jawab[$i];
        
            // Handle jabatan
            $jabatan = $request->jabatan[$i];
            if ($jabatan === 'Lainnya' && isset($request->other_jabatan[$i])) {
                $jabatan = $request->other_jabatan[$i];
            }
            $agenda->jabatan = $jabatan;
        
            // Upload foto per agenda
            $photos = [];
            if ($request->hasFile("foto.$i")) {
                foreach ($request->file("foto.$i") as $file) {
                    $filename = 'foto_' . time() . '_' . uniqid() . '.' . $file->extension();
                    $file->storeAs('public/uploads/', $filename);
                    $photos[] = $filename;
                }
            }
            $agenda->foto = json_encode($photos);
        
            $agenda->save();
        }
        
        return redirect()->route('agenda.index')->with('success', 'Agenda berhasil disimpan!');
    }


//-----------------------index-----------------------------//
    public function index(Request $request)
    {
        $query = Agenda::query();

        if ($request->has('search')) {
            $query->where('nama_kegiatan', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->search . '%')
                  ->orWhere('keterangan', 'like', '%' . $request->search . '%');
        }

        $agendas = $query->paginate($request->input('per_page', 10));
        return view('index', compact('agendas'));
    }

    public function print()
    {
        $agendas = Agenda::all();
        foreach ($agendas as $agenda) {
            $photos = [];
            if ($agenda->foto) {
                foreach (json_decode($agenda->foto, true) as $photo) {
                    $path = storage_path('app/public/uploads/'.$photo);
                    if (file_exists($path)) {
                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        $data = file_get_contents($path);
                        $photos[] = 'data:image/'.$type.';base64,'.base64_encode($data);
                    }
                }
            }
            $agenda->photos_base64 = $photos;
        }
        
        $pdf = Pdf::loadView('print', compact('agendas'));
        return $pdf->download('agenda.pdf');
    }

    public function edit($id)
    {
        $agenda = Agenda::findOrFail($id);
        
        $defaultJabatans = ['Waka Humas', 'SARPRAS', 'Kurikulum', 'Kesiswaan'];
        $isCustomJabatan = !in_array($agenda->jabatan, $defaultJabatans);
        
        return view('edit', compact('agenda', 'isCustomJabatan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string',
            'tanggal' => 'required|date',
            'deskripsi' => 'required|string',
            'keterangan' => 'required|string',
            'jabatan' => 'required|string',
            'other_jabatan' => 'nullable|string|max:255',
            'foto' => 'sometimes|array',
            'foto.*' => 'sometimes|array',
            'foto.*.*' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',

        ]);

        $agenda = Agenda::findOrFail($id);
        $agenda->nama_kegiatan = $request->nama_kegiatan;
        $agenda->tanggal = $request->tanggal;
        $agenda->deskripsi = $request->deskripsi;
        $agenda->keterangan = $request->keterangan;
        $agenda->penanggung_jawab = $request->penanggung_jawab;

        // Handle jabatan
        $jabatan = $request->jabatan;
        if ($jabatan === 'Lainnya' && $request->filled('other_jabatan')) {
            $jabatan = $request->other_jabatan;
        }
        $agenda->jabatan = $jabatan;

        
        // Handle foto
    $existingPhotos = $request->input('existing_photos', []);
    $removedPhotos = $request->input('removed_photos', []);
    
    // Hapus foto yang dipilih untuk dihapus
    foreach ($removedPhotos as $photo) {
        Storage::delete('public/uploads/'.$photo);
        $existingPhotos = array_diff($existingPhotos, [$photo]);
    }

    // Tambahkan foto baru
    $newPhotos = [];
    if ($request->hasFile('new_photos')) {
        foreach ($request->file('new_photos') as $file) {
            $filename = 'foto_'.time().'_'.uniqid().'.'.$file->extension();
            $file->storeAs('public/uploads/', $filename);
            $newPhotos[] = $filename;
        }
    }

    // Gabungkan foto lama (yang tidak dihapus) dengan foto baru
    $agenda->foto = json_encode(array_merge($existingPhotos, $newPhotos));
    
    $agenda->save();

    return redirect()->route('agenda.index')->with('success', 'Agenda berhasil diperbarui!');
}

    public function destroy($id)
    {
        $agenda = Agenda::findOrFail($id);

        // Delete related files
        if ($agenda->foto) {
            $photos = json_decode($agenda->foto, true);
            if (is_array($photos)) {
                foreach ($photos as $photo) {
                    if (Storage::exists('public/uploads/' . $photo)) {
                        Storage::delete('public/uploads/' . $photo);
                    }
                }
            }
        }

        $agenda->delete();
        return redirect()->route('agenda.index')->with('success', 'Agenda berhasil dihapus!');
    }
}