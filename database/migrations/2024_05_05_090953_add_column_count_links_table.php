<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('links', function (Blueprint $table) {
            //
            $table->string('comment')->nullable()->default(0);
            $table->string('diff_comment')->nullable()->default(0);
            $table->string('data')->nullable()->default(0);
            $table->string('diff_data')->nullable()->default(0);
            $table->string('reaction')->nullable()->default(0);
            $table->string('diff_reaction')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
