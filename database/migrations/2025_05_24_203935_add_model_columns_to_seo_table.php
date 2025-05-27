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
        Schema::table('seo', function (Blueprint $table) {
            if (!Schema::hasColumn('seo', 'model_type')) {
                $table->string('model_type')->nullable();
            }
            if (!Schema::hasColumn('seo', 'model_id')) {
                $table->unsignedBigInteger('model_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seo', function (Blueprint $table) {
            $table->dropColumn(['model_type', 'model_id']);
        });
    }
};
