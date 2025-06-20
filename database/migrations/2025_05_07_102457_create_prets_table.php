<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePretsTable extends Migration
{
    public function up()
    {
        Schema::create('prets', function (Blueprint $table) {
            $table->id('pret_id');
            $table->foreignId('lec_id')->constrained('lecteurs', 'lec_id');
            $table->foreignId('exp_id')->constrained('notice_exemplaires', 'exp_id');
            $table->date('date_pret');
            $table->date('date_retour');
            $table->date('date_retour_reelle')->nullable();
            $table->date('date_reservation')->nullable();
            $table->enum('statut', ['en_cours', 'en_retard', 'retourne', 'renouvele'])->default('en_cours');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prets');
    }
}