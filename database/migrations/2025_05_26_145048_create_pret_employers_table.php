<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePretEmployersTable extends Migration
{
    public function up()
    {
        Schema::create('pret_employers', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('doc_titre');
            $table->unsignedBigInteger('exp_id');
            $table->string('post');
            $table->string('num_tel')->nullable();
            $table->timestamps();

            // Foreign key if exp_id refers to exemplaires table
            $table->foreign('exp_id')->references('exp_id')->on('exemplaires')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pret_employers');
    }
}
