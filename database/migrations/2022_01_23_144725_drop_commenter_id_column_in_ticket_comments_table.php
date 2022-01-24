<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropCommenterIdColumnInTicketCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket_comments', function (Blueprint $table) {
            //
            $table->dropColumn('commenter_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket_comments', function (Blueprint $table) {
            //
            $table->string('commenter_id')->nullable();
        });
    }
}
