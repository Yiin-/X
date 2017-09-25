<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->unique();

            $table->string('user_uuid');
            $table->foreign('user_uuid')
                ->references('uuid')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->string('company_uuid');
            $table->foreign('company_uuid')
                ->references('uuid')->on('companies')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->string('client_uuid')->nullable();
            $table->foreign('client_uuid')
                ->references('uuid')->on('clients')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->string('name');
            $table->text('description')->nullable();

            $table->timestamps();
            $table->timestamp('archived_at')->nullable();
            $table->softDeletes();
        });

        Schema::create('task_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->unique();

            $table->string('user_uuid');
            $table->foreign('user_uuid')
                ->references('uuid')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->string('company_uuid');
            $table->foreign('company_uuid')
                ->references('uuid')->on('companies')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->string('project_uuid');
            $table->foreign('project_uuid')
                ->references('uuid')->on('projects')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->string('name');

            $table->timestamps();
            $table->timestamp('archived_at')->nullable();
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->unique();

            $table->string('user_uuid');
            $table->foreign('user_uuid')
                ->references('uuid')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->string('company_uuid');
            $table->foreign('company_uuid')
                ->references('uuid')->on('companies')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->string('task_list_uuid');
            $table->foreign('task_list_uuid')
                ->references('uuid')->on('task_lists')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->string('name');
            $table->boolean('is_completed')->default(false);

            $table->timestamps();
            $table->timestamp('archived_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('task_lists');
        Schema::dropIfExists('projects');
    }
}
