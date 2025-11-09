<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->integer('card_point')->nullable();
            $table->integer('recognition_point')->nullable();
            $table->integer('map_point')->nullable();
            $table->integer('card_time')->nullable();
            $table->integer('recognition_time')->nullable();
            $table->integer('map_time')->nullable();
            $table->boolean('is_finished')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statistics');
    }
};
