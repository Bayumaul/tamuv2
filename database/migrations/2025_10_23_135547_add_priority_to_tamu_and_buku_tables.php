<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriorityToTamuAndBukuTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_tamu', function (Blueprint $table) {
            $table->unsignedBigInteger('id_priority_category')
                ->nullable()
                ->after('kategori') // Setelah kolom kategori pengunjung lama
                ->comment('FK ke master_priority_categories');
        });

        Schema::table('data_buku_tamu', function (Blueprint $table) {
            $table->unsignedBigInteger('id_priority_category')
                ->nullable()
                ->after('tipe_layanan')
                ->comment('FK ke master_priority_categories');

            $table->integer('priority_level_snapshot')
                ->default(5)
                ->after('id_priority_category')
                ->comment('Nilai level prioritas saat antrean dibuat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_tamu', function (Blueprint $table) {
            $table->dropColumn('id_priority_category');
        });

        Schema::table('data_buku_tamu', function (Blueprint $table) {
            $table->dropColumn('id_priority_category');
            $table->dropColumn('priority_level_snapshot');
        });
    }
}
