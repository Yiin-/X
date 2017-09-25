<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->unique();

            $table->string('vendor_uuid');
            $table->foreign('vendor_uuid')
                ->references('uuid')->on('vendors')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->string('profile_uuid');
            $table->foreign('profile_uuid')
                ->references('uuid')->on('profiles')
                ->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('vendor_contacts');
    }
}
