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
            'foto' => 'sometimes|array',
            'foto.*' => 'image|nullable|max:2048',
            'penanggung_jawab' => 'required|array',
            'penanggung_jawab.*' => 'required|string',
        ]);

        $totalData = min(
            count($request->nama_kegiatan),
            count($request->tanggal),
            count($request->deskripsi),
            count($request->keterangan),
            count($request->penanggung_jawab)
        );

        for ($i = 0; $i < $totalData; $i++) {
            $agenda = new Agenda();
            $agenda->nama_kegiatan = $request->nama_kegiatan[$i];
            $agenda->tanggal = $request->tanggal[$i];
            $agenda->deskripsi = $request->deskripsi[$i];
            $agenda->keterangan = $request->keterangan[$i];
            $agenda->penanggung_jawab = $request->penanggung_jawab[$i];

            if ($request->hasFile("foto.$i")) {
                $file = $request->file("foto.$i");
                $filename = $file->store('uploads', 'public');
                $agenda->foto = $filename;
            }

            $agenda->save();
        }

        return redirect()->route('agenda.index')->with('success', 'Agenda berhasil disimpan!');
    }

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
        $pdf = Pdf::loadView('print', compact('agendas'));
        return $pdf->download('agenda.pdf');
    }

    public function edit($id)
    {
        $agenda = Agenda::findOrFail($id);
        return view('edit', compact('agenda'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string',
            'tanggal' => 'required|date',
            'deskripsi' => 'required|string',
            'keterangan' => 'required|string',
            'foto' => 'image|nullable|max:2048',
            'penanggung_jawab' => 'required|string',
        ]);

        $agenda = Agenda::findOrFail($id);
        $agenda->nama_kegiatan = $request->nama_kegiatan;
        $agenda->tanggal = $request->tanggal;
        $agenda->deskripsi = $request->deskripsi;
        $agenda->keterangan = $request->keterangan;
        $agenda->penanggung_jawab = $request->penanggung_jawab;

        if ($request->hasFile('foto')) {
            if ($agenda->foto && Storage::disk('public')->exists($agenda->foto)) {
                Storage::disk('public')->delete($agenda->foto);
            }

            $filename = $request->file('foto')->store('uploads', 'public');
            $agenda->foto = $filename;
        }

        $agenda->save();
        return redirect()->route('agenda.index')->with('success', 'Agenda berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $agenda = Agenda::findOrFail($id);

        if ($agenda->foto && Storage::disk('public')->exists($agenda->foto)) {
            Storage::disk('public')->delete($agenda->foto);
        }

        $agenda->delete();
        return redirect()->route('agenda.index')->with('success', 'Agenda berhasil dihapus!');
    }

}