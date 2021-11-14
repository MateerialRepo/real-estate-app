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
            $table->string('property_unique_id');
            $table->string('property_amount');
            $table->string('property_type');
            $table->string('is_serviced');
            $table->string('property_title');
            $table->string('year_built');
            $table->string('lga');
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->string('street_address');
            $table->string('landmark');
            $table->string('zipcode');
            $table->json('property_images');
            $table->string('bedrooms');
            $table->string('bathrooms');
            $table->string('parking');
            $table->text('property_desc');
            $table->string('lease_type');
            $table->string('preferred_religion')->nullable();
            $table->string('preferred_tribe')->nullable();
            $table->string('preferred_marital_status')->nullable();
            $table->string('preferred_employment_status')->nullable();
            $table->string('max_coresidents')->nullable();
            $table->string('preferred_gender')->nullable();
            $table->json('property_amenities')->nullable();
            $table->string('side_attraction_details')->nullable();
            $table->string('is_available');
            $table->string('is_verified')->default('not verified');
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
