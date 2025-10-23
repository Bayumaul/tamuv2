<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterPriorityCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_priority_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Nama Kategori (Contoh: Disabilitas, Lansia, Umum)');
            $table->text('description')->nullable();
            $table->integer('priority_level')->default(5)->comment('Tingkat Prioritas (1=Tertinggi, 10=Terendah)');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Opsional: Seeding awal agar tabel tidak kosong
        DB::table('master_priority_categories')->insert([
            ['name' => 'Umum', 'priority_level' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Disabilitas', 'priority_level' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lansia/Ibu Hamil', 'priority_level' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_priority_categories');
    }
}
