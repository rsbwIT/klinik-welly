<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'pasiens';
    protected $fillable = [
        'tanggal_masuk',
        'jam_masuk',
        'no_reg',
        'no_rkm_medis',
        'no_rawat',
        'kd_dokter',
        'dokter_dituju',
        'kd_poli',
        'unit_poli',
        'p_jawab',
        'almt_pj',
        'hubunganpj',
        'kd_pj',
        'jenis_bayar',
        'no_kartu',
        'status_poli',
        'asal_rujukan',
        'nama_lengkap',
        'no_telpon',
        'jenis_kelamin',
        'tempat_lahir',
        'tgl_lahir',
        'agama',
        'nama_ibu',
        'gol_darah',
        'kelurahan',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'alamat_lengkap',
        'petugas'
    ];
}
