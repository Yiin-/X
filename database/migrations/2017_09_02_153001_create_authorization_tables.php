<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorizationTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Roles can have permissions to access data of other companies of same account.
         * Parent roles can manage their child roles.
         * Child roles can't have more permissions than it's parent role.
         * Role can be created for specific user
         */
        Schema::create('roles', function (Blueprint $table) {
            $table->string('uuid')->unique();

            $table->string('roleable_type');
            $table->string('roleable_id');

            $table->string('parent_role_uuid')->nullable();
            $table->string('name')->nullable();

            $table->timestamps();
        });

        /**
         * Lookup table for permission actions such as create | edit | update | delete
         */
        Schema::create('permission_types', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->unique();

            $table->timestamps();
        });

        /**
         * Permission defines access to permissible data.
         * Any document can be permissible.
         * Permissions are globaly scoped.
         * There are no negative permissions, i.e. permission
         * can't deny access to anything, it can only give access.
         * As long as user has permission, it can access permissible resource.
         */
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('scope'); // App\Domain\Constants\Permission\Scopes::class
            $table->string('scope_id');
            $table->string('permissible_type');

            $table->integer('permission_type_id'); // App\Domain\Constants\Permission\Actions::class

            $table->timestamps();
        });

        /**
         * Any role can have any permission, as long as it is
         * in it's parent role scope.
         * Theorically role may have permission to access data outside of
         * account scope, but in this implementation we're not going to do that.
         */
        Schema::create('role_permission', function (Blueprint $table) {
            $table->string('role_uuid');
            $table->foreign('role_uuid')
                ->references('uuid')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->integer('permission_id')->unsigned();
            $table->foreign('permission_id')
                ->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');
        });

        /**
         * User can belong to set of roles, and therefore inherit permissions
         * of these roles.
         */
        Schema::create('user_role', function (Blueprint $table) {
            $table->string('user_uuid');
            $table->foreign('user_uuid')
                ->references('uuid')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->string('role_uuid');
            $table->foreign('role_uuid')
                ->references('uuid')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('role_permission');
        Schema::dropIfExists('user_role');
        Schema::dropIfExists('user_permission');
    }
}
