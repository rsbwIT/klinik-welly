<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Padepokan extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'peserta_padepokan';
    protected $fillable = [
        'tanggal_masuk',
        'nama_lengkap',
        'angkatan',
        'jenjang',
        'no_telpon',
        'tempat_lahir',
        'tgl_lahir',
        'jenis_kelamin',
        'agama',
        'gol_darah',
        'kelurahan',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'alamat_lengkap',
        'petugas'
    ];
}
