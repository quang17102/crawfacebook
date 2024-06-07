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
        //
        Schema::table('links', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('active')->nullable()->default(1);
            $table->softDeletes();
            $table->string('is_on_at')->nullable()->default(now()->format('Y-m-d H:i:s'));
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
