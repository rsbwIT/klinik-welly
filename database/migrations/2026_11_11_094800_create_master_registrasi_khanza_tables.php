<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Master Dokter
        if (!Schema::hasTable('master_dokter')) {
            Schema::create('master_dokter', function (Blueprint $table) {
                $table->id();
                $table->string('kd_dokter', 20)->unique();
                $table->string('nm_dokter', 100);
                $table->enum('jk', ['L', 'P'])->default('L');
                $table->string('spesialis', 100)->nullable();
                $table->string('no_telp', 30)->nullable();
                $table->enum('status', ['1', '0'])->default('1');
                $table->timestamps();
            });

            DB::table('master_dokter')->insert([
                [
                    'kd_dokter' => 'D0000001',
                    'nm_dokter' => 'dr. Welly',
                    'jk' => 'L',
                    'spesialis' => 'Praktisi Medis & Dokter Penanggung Jawab',
                    'no_telp' => '081234567890',
                    'status' => '1',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'kd_dokter' => 'D0000002',
                    'nm_dokter' => 'dr. Feri Jumaidi, Sp.B',
                    'jk' => 'L',
                    'spesialis' => 'Spesialis Bedah',
                    'no_telp' => '082198765432',
                    'status' => '1',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'kd_dokter' => 'D0000030',
                    'nm_dokter' => 'dr. Sanjoto Santibudi, Sp.KFR',
                    'jk' => 'L',
                    'spesialis' => 'Spesialis Kedokteran Fisik & Rehabilitasi',
                    'no_telp' => '081345678901',
                    'status' => '1',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // 2. Master Poliklinik / Unit
        if (!Schema::hasTable('master_poli')) {
            Schema::create('master_poli', function (Blueprint $table) {
                $table->id();
                $table->string('kd_poli', 20)->unique();
                $table->string('nm_poli', 100);
                $table->enum('status', ['1', '0'])->default('1');
                $table->timestamps();
            });

            DB::table('master_poli')->insert([
                [
                    'kd_poli' => 'U001',
                    'nm_poli' => 'Poli Perawatan Luka Modern & Bedah Minor',
                    'status' => '1',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'kd_poli' => 'U002',
                    'nm_poli' => 'Poli Umum',
                    'status' => '1',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'kd_poli' => 'FIS',
                    'nm_poli' => 'FIS Poliklinik Rehabilitasi Medik',
                    'status' => '1',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // 3. Master Penjab / Asuransi / Jenis Bayar
        if (!Schema::hasTable('master_penjab')) {
            Schema::create('master_penjab', function (Blueprint $table) {
                $table->id();
                $table->string('kd_pj', 20)->unique();
                $table->string('png_jawab', 100);
                $table->string('nama_perusahaan', 100)->nullable();
                $table->enum('status', ['1', '0'])->default('1');
                $table->timestamps();
            });

            DB::table('master_penjab')->insert([
                [
                    'kd_pj' => 'PJ001',
                    'png_jawab' => 'UMUM',
                    'nama_perusahaan' => 'Umum / Mandiri / Tunai',
                    'status' => '1',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'kd_pj' => 'PJ002',
                    'png_jawab' => 'BPJS',
                    'nama_perusahaan' => 'BPJS Kesehatan Mandiri / PBI',
                    'status' => '1',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'kd_pj' => 'PJ003',
                    'png_jawab' => 'ASURANSI',
                    'nama_perusahaan' => 'Asuransi Swasta Admedika / Prudential / Sinarmas',
                    'status' => '1',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'kd_pj' => 'PJ004',
                    'png_jawab' => 'PERUSAHAAN',
                    'nama_perusahaan' => 'Kerjasama Perusahaan / PKS',
                    'status' => '1',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'kd_pj' => 'PJ005',
                    'png_jawab' => 'GRATIS',
                    'nama_perusahaan' => 'Bakti Sosial / Keringanan Dokter',
                    'status' => '1',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('master_dokter');
        Schema::dropIfExists('master_poli');
        Schema::dropIfExists('master_penjab');
    }
};

