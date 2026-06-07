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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100); 
            $table->string('floor', 10); 
            $table->string('category', 50); 
            $table->time('open_time'); 
            $table->time('close_time'); 
            $table->string('tel', 20)->nullable(); 
            $table->string('description', 500)->nullable(); 
            $table->boolean('is_temporarily_closed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
