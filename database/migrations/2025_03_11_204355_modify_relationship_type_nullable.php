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
        Schema::table('personality', function (Blueprint $table) {
            $table->string('relationship_type')->nullable()->change();
            $table->string('dating_intentions')->nullable()->change();
            $table->string('min_age')->nullable()->change();
            $table->string('max_age')->nullable()->change();
            $table->string('like_to_have_more_childran')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personality', function (Blueprint $table) {
            //
        });
    }
};
