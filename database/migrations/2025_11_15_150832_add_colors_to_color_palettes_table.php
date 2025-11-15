<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('color_palettes', function (Blueprint $table) {
            $table->string('background');
            $table->string('surface');
            $table->string('surface_alt');
            $table->string('text');
            $table->string('text_muted');
            $table->string('accent');
            $table->string('accent_text');
            $table->string('border');
        });
    }

    public function down(): void
    {
        Schema::table('color_palettes', function (Blueprint $table) {
            $table->dropColumn([
                'background',
                'surface',
                'surface_alt',
                'text',
                'text_muted',
                'accent',
                'accent_text',
                'border',
            ]);
        });
    }
};
