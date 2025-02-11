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
        Schema::create('url_hits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('url_id')->constrained('urls');
            $table->timestamp('hit_time');
            $table->integer('hit_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('url_hits');
    }
};
