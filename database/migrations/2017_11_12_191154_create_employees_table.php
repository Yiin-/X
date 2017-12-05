<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->unique();

            $table->string('user_uuid')->nullable();
            $table->foreign('user_uuid')
                ->references('uuid')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->string('company_uuid')->nullable();
            $table->foreign('company_uuid')
                ->references('uuid')->on('companies')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('job_title')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            $table->boolean('is_disabled')->default(false);

            $table->timestamps();
            $table->timestamp('archived_at')->nullable();
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
        Schema::dropIfExists('employees');
    }
}
