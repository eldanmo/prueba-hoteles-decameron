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
        Schema::table('hotel', function (Blueprint $table) {
            $table->unique('nombre');
            $table->unique('nit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel', function (Blueprint $table) {
            $table->dropUnique(['nombre']);
            $table->dropUnique(['nit']);
        });
    }
};
