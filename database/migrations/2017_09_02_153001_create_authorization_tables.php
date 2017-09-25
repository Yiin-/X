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
         * Roles are per-company.
         * Roles can't be moved across companies.
         * Roles can have permissions to access data of other companies.
         * Parent roles can manage their child roles.
         * Child roles can't have more permissions than it's parent role.
         */
        Schema::create('roles', function (Blueprint $table) {
            $table->string('uuid')->unique();

            $table->string('company_uuid');
            $table->foreign('company_uuid')
                ->references('uuid')->on('companies')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->string('parent_role_uuid')->nullable();

            $table->string('name');

            $table->timestamps();
        });

        /**
         * Permission defines access to permissible data.
         * Any entity can be permissible.
         * Permissions are globaly scoped.
         * Permissions are used only internaly.
         * There are no negative permissions, i.e. permission
         * can't deny access to anything, it can only give access.
         * As long as user has permission, it can access permissible resource
         * that permission controls.
         */
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');

            $table->string('type');

            $table->string('permissible_type');
            $table->string('permissible_uuid')->nullable();

            $table->timestamps();
        });

        /**
         * Any role can have any permission, as long as it is
         * in it's parent role scope.
         * Theorically role may have permission to access data outside of
         * account scope, but in this implementation we're not going to do that.
         */
        Schema::create('role_permission', function (Blueprint $table) {
            $table->increments('id');

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
            $table->increments('id');

            $table->string('user_uuid');
            $table->foreign('user_uuid')
                ->references('uuid')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->string('role_uuid');
            $table->foreign('role_uuid')
                ->references('uuid')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');
        });

        /**
         * User can have permission, that is given by another user
         * who has access to both given permission and the user that
         * permission is given to.
         */
        Schema::create('user_permission', function (Blueprint $table) {
            $table->increments('id');

            $table->string('user_uuid');
            $table->foreign('user_uuid')
                ->references('uuid')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->integer('permission_id')->unsigned();
            $table->foreign('permission_id')
                ->references('id')->on('permissions')
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
