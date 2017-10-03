<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_log', function (Blueprint $table) {
            $table->increments('id');

            $table->string('user_uuid')->nullable();
            $table->foreign('user_uuid')
                ->references('uuid')->on('users')
                ->onDelete('set null')->onUpdate('cascade');

            $table->string('action');
            $table->string('document_type');
            $table->string('document_uuid');
            $table->text('changes')->nullable();
            $table->text('json_backup');

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
        Schema::dropIfExists('activity_log');
    }
}
