<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPasien extends Model
{
    use HasFactory;

    protected $table = 'master_pasien';

    protected $fillable = [
        'no_rkm_medis',
        'no_ktp',
        'nm_pasien',
        'jk',
        'tmp_lahir',
        'tgl_lahir',
        'nm_ibu',
        'gol_darah',
        'agama',
        'stts_nikah',
        'no_tlp',
        'pekerjaan',
        'pnd',
        'keluarga',
        'namakeluarga',
        'kd_pj',
        'no_peserta',
        'alamat',
        'kelurahan',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'tgl_daftar',
    ];
}

