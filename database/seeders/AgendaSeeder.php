<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agenda;

class AgendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Agenda::create([
            'nama_kegiatan' => 'Mengajar Matematika',
            'tanggal' => '2023-10-01',
            'deskripsi' => 'Kegiatan mengajar matematika untuk kelas 5.',
            'foto' => 'uploads/foto.jpg', // Pastikan path foto sesuai
            'penanggung_jawab' => 'John Doe',
        ]);
    }
}
