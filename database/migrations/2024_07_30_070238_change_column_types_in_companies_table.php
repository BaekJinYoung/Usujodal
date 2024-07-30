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
        Schema::table('companies', function (Blueprint $table) {
            $table->longText('content')->change();
        });

        Schema::table('youtubes', function (Blueprint $table) {
            $table->longText('content')->change();
        });

        Schema::table('shares', function (Blueprint $table) {
            $table->longText('content')->change();
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->longText('content')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            //
        });
    }
};
