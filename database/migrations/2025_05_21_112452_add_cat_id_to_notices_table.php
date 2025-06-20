<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('notices', function (Blueprint $table) {
        $table->integer('cat_id')->nullable()->after('doc_id'); // Add category id column, nullable for now

        // Add foreign key constraint referencing categories.cat_id (optional but recommended)
        $table->foreign('cat_id')->references('cat_id')->on('categories')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('notices', function (Blueprint $table) {
        $table->dropForeign(['cat_id']); // Drop foreign key first
        $table->dropColumn('cat_id');    // Then drop the column
    });
}

};
