<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Global scope platform accounts, completly isolated from other accounts.
         * Has their own companies. Company may have several users who manages an account.
         */
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->unique();

            $table->string('name');
            $table->string('site_address');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->unique();

            $table->string('name');

            $table->string('email');
            $table->string('logo_url')->nullable();

            $table->string('account_uuid');
            $table->foreign('account_uuid')
                ->references('uuid')->on('accounts')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->unique();

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('job_position')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->unique();

            $table->string('username');
            $table->string('password');

            $table->string('account_uuid');
            $table->foreign('account_uuid')
                ->references('uuid')->on('accounts')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->string('profile_uuid');
            $table->foreign('profile_uuid')
                ->references('uuid')->on('profiles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->unique(['account_uuid', 'username']);

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('user_company', function (Blueprint $table) {
            $table->increments('id');

            $table->string('user_uuid');
            $table->foreign('user_uuid')
                ->references('uuid')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->string('company_uuid');
            $table->foreign('company_uuid')
                ->references('uuid')->on('companies')
                ->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('user_company');
        Schema::dropIfExists('profiles');
        Schema::dropIfExists('users');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('accounts');
    }
}
