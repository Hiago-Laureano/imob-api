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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string("name", 255);
            $table->decimal("price", 10, 2);
            $table->text("location");
            $table->longText("description");
            $table->integer("bedrooms");
            $table->integer("bathrooms");
            $table->boolean("for_rent");
            $table->integer("max_tenants")->nullable();
            $table->integer("min_contract_time")->nullable();
            $table->boolean("accept_animals")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
