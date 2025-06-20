<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateDemandesPretTable extends Migration
{
    public function up()
    {
      Schema::create('demandes_pret', function (Blueprint $table) {
    $table->id(); // auto-incrementing primary key
    $table->foreignId('lec_id')->constrained('lecteurs'); // Foreign key to the lecteurs table
    $table->foreignId('exp_id')->constrained('notice_exemplaires'); // Fixed foreign key to exemplaires table
    $table->date('date_demande');
    $table->enum('statut', ['En attente', 'Acceptée', 'Refusée']);
    $table->string('statu')->default('pret');
    $table->text('motif_refus')->nullable();
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
        Schema::dropIfExists('demandes_pret');
    }
}
