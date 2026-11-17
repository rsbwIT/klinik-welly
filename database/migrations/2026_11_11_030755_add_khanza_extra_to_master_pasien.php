<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('master_pasien', function (Blueprint $table) {
            if (!Schema::hasColumn('master_pasien', 'pnd')) {
                $table->string('pnd', 30)->nullable()->after('pekerjaan');
            }
            if (!Schema::hasColumn('master_pasien', 'keluarga')) {
                $table->string('keluarga', 50)->nullable()->after('pnd');
            }
            if (!Schema::hasColumn('master_pasien', 'namakeluarga')) {
                $table->string('namakeluarga', 100)->nullable()->after('keluarga');
            }
            if (!Schema::hasColumn('master_pasien', 'kd_pj')) {
                $table->string('kd_pj', 30)->nullable()->after('namakeluarga');
            }
        });
    }

    public function down()
    {
    }
};
