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
        Schema::create('personality', function (Blueprint $table) {
            $table->id();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->json('interests'); // Use 'json' data type for interests
           // $table->string('interests');
            $table->string('hometown');
            $table->string('height');
            $table->string('nationality');
            $table->string('relationship_type');
            $table->string('dating_intentions');
            $table->string('min_age');
            $table->string('max_age');
            $table->string('like_to_have_more_childran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personality');
    }
};
