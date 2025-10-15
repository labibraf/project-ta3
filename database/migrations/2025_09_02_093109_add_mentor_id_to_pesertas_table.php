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
        Schema::table('pesertas', function (Blueprint $table) {
            $table->foreignId('mentor_id')->nullable()->after('bagian_id');

            $table->foreign('mentor_id')->references('id')->on('mentors')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pesertas', function (Blueprint $table) {
            $table->dropForeign(['mentor_id']);
            $table->dropColumn(['mentor_id']);
        });
    }
};
