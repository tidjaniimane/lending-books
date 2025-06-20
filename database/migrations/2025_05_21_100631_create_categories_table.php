<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->integer('cat_id')->primary(); // custom primary key, NOT auto-increment
            $table->string('nom');
            $table->integer('parent_id')->nullable(); // for parent category ID
            $table->timestamps();

            // Foreign key constraint referencing the same table (self-referencing)
            $table->foreign('parent_id')->references('cat_id')->on('categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories'); 
    }
};
