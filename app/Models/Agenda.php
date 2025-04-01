<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;

    protected $table = 'agendas'; // Pastikan tabel sesuai

    protected $fillable = [
        'nama_kegiatan',
        'tanggal',
        'deskripsi',
        'foto',
        'penanggung_jawab', // Sudah diperbarui
        'keterangan', // Ditambahkan
    ];

    public $timestamps = true; // Ubah ke false jika tidak ada 'created_at' dan 'updated_at'

    // ✅ Fungsi Accessor untuk mendapatkan URL Foto
    public function getFotoUrlAttribute()
    {
        return $this->foto ? asset('storage/' . $this->foto) : null;
    }
}
