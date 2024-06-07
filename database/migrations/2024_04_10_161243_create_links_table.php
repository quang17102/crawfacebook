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
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->string('time')->default(0);
            $table->longText('title')->nullable();
            $table->longText('content')->nullable();
            $table->string('delay')->default(0);
            $table->string('status')->default(1);
            $table->string('is_scan')->default(0);
            $table->longText('note')->nullable();
            $table->string('link_or_post_id');
            $table->string('type')->nullable();
            $table->string('end_cursor')->nullable();
            // $table->string('is_on_at')->nullable()->default(now()->format('Y-m-d H:i:S'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
