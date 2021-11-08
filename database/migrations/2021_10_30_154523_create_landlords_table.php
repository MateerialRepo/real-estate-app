<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandlordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('landlords', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('landlord_id')->default("LND-" . mt_rand(10000000,99999999) . "-BRC");
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->string('gender')->nullable();
            $table->string('dob')->nullable();
            $table->string('occupation')->nullable();
            $table->string('address')->nullable();
            $table->string('landmark')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('profile_pic')->nullable();
            $table->string('kyc_type')->nullable();
            $table->string('kyc_id')->nullable();
            $table->string('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('landlords');
    }
}
