<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoticesTable extends Migration
{
    public function up()
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->id('doc_id');
            $table->string('doc_titre');
            $table->string('doc_auteur');
            $table->string('isbn')->nullable();
            $table->integer('annee_publication')->nullable();
            $table->text('description')->nullable();
            $table->string('editeur')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notices');
    }
}
