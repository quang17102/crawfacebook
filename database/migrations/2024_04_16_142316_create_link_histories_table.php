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
        Schema::create('link_histories', function (Blueprint $table) {
            $table->id();
            // $table->string('comment_first')->default(0);
            // $table->string('comment_second')->default(0);
            // $table->string('data_first')->default(0);
            // $table->string('data_second')->default(0);
            // $table->string('emotion_first')->default(0);
            // $table->string('emotion_second')->default(0);
            $table->unsignedBigInteger('link_id')->nullable();
            $table->foreign('link_id')->references('id')->on('links')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('link_histories');
    }
};
