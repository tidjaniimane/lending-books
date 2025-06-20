<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnedTable extends Migration
{
    public function up()
    {
        Schema::create('returned', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('is_admin')->default(0);
            $table->string('doc_titre');
            $table->timestamps();
        });
    }
    

    public function down()
    {
        Schema::dropIfExists('returned');
    }
}
