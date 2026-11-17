<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pasiens', function (Blueprint $table) {
            if (!Schema::hasColumn('pasiens', 'no_reg')) {
                $table->string('no_reg', 10)->nullable()->after('jam_masuk');
            }
            if (!Schema::hasColumn('pasiens', 'kd_dokter')) {
                $table->string('kd_dokter', 20)->nullable()->after('no_rawat');
            }
            if (!Schema::hasColumn('pasiens', 'dokter_dituju')) {
                $table->string('dokter_dituju', 100)->nullable()->after('kd_dokter');
            }
            if (!Schema::hasColumn('pasiens', 'kd_poli')) {
                $table->string('kd_poli', 20)->nullable()->after('dokter_dituju');
            }
            if (!Schema::hasColumn('pasiens', 'unit_poli')) {
                $table->string('unit_poli', 100)->nullable()->after('kd_poli');
            }
            if (!Schema::hasColumn('pasiens', 'p_jawab')) {
                $table->string('p_jawab', 100)->nullable()->after('unit_poli');
            }
            if (!Schema::hasColumn('pasiens', 'almt_pj')) {
                $table->string('almt_pj', 200)->nullable()->after('p_jawab');
            }
            if (!Schema::hasColumn('pasiens', 'hubunganpj')) {
                $table->string('hubunganpj', 50)->nullable()->after('almt_pj');
            }
            if (!Schema::hasColumn('pasiens', 'kd_pj')) {
                $table->string('kd_pj', 20)->nullable()->after('hubunganpj');
            }
            if (!Schema::hasColumn('pasiens', 'jenis_bayar')) {
                $table->string('jenis_bayar', 50)->default('UMUM')->after('kd_pj');
            }
            if (!Schema::hasColumn('pasiens', 'no_kartu')) {
                $table->string('no_kartu', 50)->nullable()->after('jenis_bayar');
            }
            if (!Schema::hasColumn('pasiens', 'status_poli')) {
                $table->string('status_poli', 30)->default('Baru')->after('no_kartu');
            }
            if (!Schema::hasColumn('pasiens', 'asal_rujukan')) {
                $table->string('asal_rujukan', 100)->nullable()->after('status_poli');
            }
        });
    }

    public function down()
    {
    }
};

