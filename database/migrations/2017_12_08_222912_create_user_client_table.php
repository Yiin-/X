<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_client', function (Blueprint $table) {
            $table->string('user_uuid');
            $table->foreign('user_uuid')
                ->references('uuid')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->string('client_uuid');
            $table->foreign('client_uuid')
                ->references('uuid')->on('clients')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_client');
    }
}
