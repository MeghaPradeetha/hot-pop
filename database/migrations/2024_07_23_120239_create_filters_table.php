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
        Schema::create('filters', function (Blueprint $table) {
            $table->id();
			$table->string('user_id')->nullable();
			$table->string('relationship_type')->nullable();
			$table->string('preferences')->nullable();
			$table->string('children')->nullable();
			$table->string('min_age')->nullable();
			$table->string('max_age')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filters');
    }
};
