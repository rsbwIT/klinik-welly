<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPenjab extends Model
{
    use HasFactory;
    protected $table = 'master_penjab';
    protected $fillable = ['kd_pj', 'png_jawab', 'nama_perusahaan', 'status'];
}

