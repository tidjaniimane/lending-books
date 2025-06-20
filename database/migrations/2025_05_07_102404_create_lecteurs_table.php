<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLecteursTable extends Migration
{
    public function up()
    {
        Schema::create('lecteurs', function (Blueprint $table) {
            $table->id('lec_id');
            $table->string('lec_nom');
            $table->string('lec_prenom');
            $table->string('lec_adress')->nullable();
            $table->string('lec_tel')->nullable();
            $table->string('lec_password');
            $table->string('lec_email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lecteurs');
    }
}