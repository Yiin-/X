<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppliedCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applied_credits', function (Blueprint $table) {
            $table->increments('id');

            $table->string('credit_uuid');
            $table->string('billable_type');
            $table->string('billable_id');
            $table->decimal('amount', 13, 2);
            $table->string('currency_code');

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
        Schema::dropIfExists('applied_credits');
    }
}
