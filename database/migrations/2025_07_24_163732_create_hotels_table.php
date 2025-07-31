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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('city');
            $table->string('nit')->unique();
            $table->string('email')->unique();
            $table->string('phone');
            $table->integer('max_rooms');
            $table->timestamps();
            $table->softDeletes();
            
            // Restricción única compuesta: mismo nombre solo si está en diferente ciudad
            $table->unique(['name', 'city'], 'hotels_name_city_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
