<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoticeExemplairesTable extends Migration
{
    public function up()
    {
        Schema::create('notice_exemplaires', function (Blueprint $table) {
            $table->id('exp_id');
            $table->foreignId('doc_id')->constrained('notices', 'doc_id');
            $table->string('exp_cote')->unique();
            $table->enum('etat', ['disponible', 'emprunte', 'reserve', 'perdu', 'en_reparation'])->default('disponible');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notice_exemplaires');
    }
}