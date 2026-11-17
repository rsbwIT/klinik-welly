<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoapPasien extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'soappasien';
    protected $fillable = [
        'tanggal_input_soap',
        'jam_input_soap',
        'no_rawat',
        'suhu',
        'tensi',
        'respirasi',
        'spo2',
        'gcs',
        'alergi',
        'nadi',
        'tinggi_badan',
        'berat_badan',
        'kesadaran',
        'subjek',
        'objek',
        'asesmen',
        'plan',
        'intruksi',
        'evaluasi',
        'petugas',
        'nip',
        'nama_petugas',
        'profesi',
        'lingkar_perut',
    ];
}
