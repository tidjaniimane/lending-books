<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatistiquesTable extends Migration
{
    public function up()
    {
        Schema::create('statistiques', function (Blueprint $table) {
            $table->id('sta_id');
            $table->string('sta_type');
            $table->integer('sta_nboccurence');
            $table->date('sta_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('statistiques');
    }
}