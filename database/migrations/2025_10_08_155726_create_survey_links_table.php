<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveyLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_links', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nama atau Deskripsi Survei (Contoh: Survei Kepuasan Pelayanan Umum)');
            $table->text('caption')->comment('Isi Pesan WA Lengkap, termasuk link.');
            $table->boolean('is_active')->default(false)->comment('Status apakah link ini sedang digunakan/aktif.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('survey_links');
    }
}
