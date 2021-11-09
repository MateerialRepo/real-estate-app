<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained();
            $table->string('ticket_unique_id')->default("TKT-" . mt_rand(10000000,99999999) . "-BRC");
            $table->string('ticket_status');
            $table->string('ticket_title');
            $table->string('ticket_category');
            $table->text('description')->nullable();
            $table->json('ticket_img')->nullable();
            $table->string('landlord_id');
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
        Schema::dropIfExists('tickets');
    }
}
