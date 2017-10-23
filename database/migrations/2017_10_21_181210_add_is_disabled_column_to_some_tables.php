<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsDisabledColumnToSomeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ([
            'products',
            'clients',
            'invoices',
            'payments',
            'credits',
            'quotes',
            'expenses',
            'vendors',
            'projects',
            'task_lists',
            'tasks'
        ] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->boolean('is_disabled')->default(false);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
