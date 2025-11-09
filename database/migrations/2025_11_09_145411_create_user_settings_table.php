<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->integer('width')->nullable();
            $table->integer('font_size')->nullable();
            $table->integer('letter_spacing')->nullable();
            $table->integer('line_height')->nullable();
            $table->foreignId('color_palette_id')->constrained('color_palettes');
            $table->boolean('show_card_game')->nullable();
            $table->boolean('show_recognition_game')->nullable();
            $table->boolean('show_map_game')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
