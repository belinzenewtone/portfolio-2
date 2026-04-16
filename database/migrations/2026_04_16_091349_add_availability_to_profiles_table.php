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
        Schema::table('profiles', function (Blueprint $table) {
            $table->boolean('is_available')->default(true)->after('cv_url');
            $table->string('availability_text')->default('Open to opportunities')->after('is_available');
            $table->boolean('show_availability')->default(true)->after('availability_text');
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['is_available', 'availability_text', 'show_availability']);
        });
    }
};
