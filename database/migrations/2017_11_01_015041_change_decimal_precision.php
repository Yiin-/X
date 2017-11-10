<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDecimalPrecision extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // https://github.com/laravel/framework/issues/1186#issuecomment-248853309
        Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 13, 2)->change();
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('partial', 13, 2)->change();
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->decimal('partial', 13, 2)->change();
        });

        Schema::table('bill_items', function (Blueprint $table) {
            $table->decimal('cost', 13, 2)->change();
        });

        Schema::table('credits', function (Blueprint $table) {
            $table->decimal('amount', 13, 2)->change();
            $table->decimal('balance', 13, 2)->change();
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->decimal('amount', 13, 2)->change();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('amount', 13, 2)->change();
            $table->decimal('refunded', 13, 2)->change();
        });
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
