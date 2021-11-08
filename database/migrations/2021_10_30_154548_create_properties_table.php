<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('landlord_id')->constrained();
            $table->string('property_unique_id')->default("PRP-" . mt_rand(10000000,99999999) . "-BRC");
            $table->string('title');
            $table->string('verified');
            $table->string('property_type');
            $table->string('property_amount');
            $table->json('property_images');
            $table->string('bedrooms');
            $table->string('bathrooms');
            $table->string('serviced');
            $table->string('parking');
            $table->string('availability');
            $table->string('year_built');
            $table->string('street');
            $table->string('lga');
            $table->string('state');
            $table->string('country');
            $table->string('zipcode');
            $table->string('preferred_religion')->nullable();
            $table->string('preferred_tribe')->nullable();
            $table->string('preferred_marital_status')->nullable();
            $table->string('preferred_employment_status')->nullable();
            $table->string('preferred_gender')->nullable();
            $table->string('max_coresidents')->nullable();
            $table->string('amenities')->nullable();
            $table->string('side_attraction_categories')->nullable();
            $table->string('side_attraction_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties');
    }
}
