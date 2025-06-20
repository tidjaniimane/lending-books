<?php

// Migration file: create_employes_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployesTable extends Migration
{
    public function up()
    {
        Schema::create('employes', function (Blueprint $table) {
            $table->id('emp_id');
            $table->string('emp_nom');
            $table->string('emp_prenom');
            $table->string('emp_poste')->nullable();
            $table->string('password');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employes');
    }
}

// Also update your lecteurs table to add type_lecteur field
class AddTypeLecteurToLecteursTable extends Migration
{
    public function up()
    {
        Schema::table('lecteurs', function (Blueprint $table) {
            $table->enum('type_lecteur', ['lecteur', 'employe'])->default('lecteur')->after('lec_email');
            $table->unsignedBigInteger('emp_id')->nullable()->after('type_lecteur');
            $table->foreign('emp_id')->references('emp_id')->on('employes')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('lecteurs', function (Blueprint $table) {
            $table->dropForeign(['emp_id']);
            $table->dropColumn(['type_lecteur', 'emp_id']);
        });
    }
}

