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
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('visitor_name')->nullable()->after('notes');
            $table->string('visitor_institution')->nullable()->after('visitor_name');
            $table->string('tracking_token', 64)->nullable()->unique()->after('visitor_institution');
            $table->tinyInteger('feedback_rating')->nullable()->after('tracking_token')->comment('1: Tidak Puas, 2: Cukup, 3: Puas, 4: Sangat Puas');
            $table->text('feedback_comment')->nullable()->after('feedback_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn([
                'visitor_name',
                'visitor_institution',
                'tracking_token',
                'feedback_rating',
                'feedback_comment',
            ]);
        });
    }
};
