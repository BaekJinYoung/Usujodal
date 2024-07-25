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
        Schema::table('shares', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
