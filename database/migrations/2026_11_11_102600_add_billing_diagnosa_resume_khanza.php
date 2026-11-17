<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pasiens', function (Blueprint $table) {
            if (!Schema::hasColumn('pasiens', 'status_bayar')) {
                $table->string('status_bayar', 30)->default('Belum')->after('status_poli');
            }
            if (!Schema::hasColumn('pasiens', 'status_periksa')) {
                $table->string('status_periksa', 50)->default('Belum')->after('status_bayar');
            }
            if (!Schema::hasColumn('pasiens', 'biaya_reg')) {
                $table->double('biaya_reg')->default(40000)->after('status_periksa');
            }
            if (!Schema::hasColumn('pasiens', 'no_sep')) {
                $table->string('no_sep', 50)->nullable()->after('biaya_reg');
            }
            if (!Schema::hasColumn('pasiens', 'potensi_prb')) {
                $table->string('potensi_prb', 50)->nullable()->after('no_sep');
            }
        });

        // 2. Buat tabel billing_pasien jika belum ada
        if (!Schema::hasTable('billing_pasien')) {
            Schema::create('billing_pasien', function (Blueprint $table) {
                $table->id();
                $table->string('no_rawat', 255)->index();
                $table->string('no_rkm_medis', 20)->nullable();
                $table->string('nm_pasien', 100)->nullable();
                $table->date('tgl_byr')->nullable();
                $table->time('jam_byr')->nullable();
                $table->double('biaya_registrasi')->default(40000);
                $table->double('biaya_tindakan')->default(0);
                $table->double('biaya_obat')->default(0);
                $table->double('biaya_lab')->default(0);
                $table->double('total_bayar')->default(40000);
                $table->enum('status_bayar', ['Sudah', 'Belum'])->default('Belum');
                $table->string('petugas', 50)->nullable();
                $table->timestamps();
            });
        }

        // 3. Buat tabel diagnosa_pasien (ICD-10) jika belum ada
        if (!Schema::hasTable('diagnosa_pasien')) {
            Schema::create('diagnosa_pasien', function (Blueprint $table) {
                $table->id();
                $table->string('no_rawat', 255)->index();
                $table->string('kd_penyakit', 20)->nullable();
                $table->string('nm_penyakit', 255)->nullable();
                $table->enum('status', ['Utama', 'Sekunder'])->default('Utama');
                $table->enum('prioritas', ['1', '2', '3'])->default('1');
                $table->timestamps();
            });
        }

        // 4. Buat tabel resume_pasien jika belum ada
        if (!Schema::hasTable('resume_pasien')) {
            Schema::create('resume_pasien', function (Blueprint $table) {
                $table->id();
                $table->string('no_rawat', 255)->unique();
                $table->string('keluhan_utama', 255)->nullable();
                $table->text('jalannya_penyakit')->nullable();
                $table->text('pemeriksaan_fisik')->nullable();
                $table->string('diagnosa_utama', 255)->nullable();
                $table->string('diagnosa_sekunder', 255)->nullable();
                $table->text('prosedur_tindakan')->nullable();
                $table->text('terapi_obat')->nullable();
                $table->text('anjuran_edukasi')->nullable();
                $table->string('kondisi_pulang', 100)->default('Membaik');
                $table->string('dokter_dpjp', 100)->default('dr. Welly');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
    }
};

