<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Static data tables
         */
        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');

            $table->string('capital', 255)->nullable();
            $table->string('citizenship', 255)->nullable();
            $table->string('country_code', 3)->default('');
            $table->string('currency', 255)->nullable();
            $table->string('currency_code', 255)->nullable();
            $table->string('currency_sub_unit', 255)->nullable();
            $table->string('full_name', 255)->nullable();
            $table->string('iso_3166_2', 2)->default('');
            $table->string('iso_3166_3', 3)->default('');
            $table->string('name', 255)->default('');
            $table->string('region_code', 3)->default('');
            $table->string('sub_region_code', 3)->default('');
            $table->boolean('eea')->default(0);

            $table->timestamps();
        });

        Schema::create('currencies', function (Blueprint $table) {
            $table->increments('id');

            $table->string('code');
            $table->string('name');
            $table->string('symbol');
            $table->integer('precision');
            $table->string('iso_3166_2')->nullable();
            $table->decimal('eur_rate', 15, 6)->default(1);

            $table->timestamps();
        });

        Schema::create('languages', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('locale');

            $table->timestamps();
        });

        Schema::create('industries', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');

            $table->timestamps();
        });

        Schema::create('company_sizes', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');

            $table->timestamps();
        });

        Schema::create('timezones', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('location');

            $table->timestamps();
        });

        Schema::create('gateway_types', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('alias');

            $table->timestamps();
        });

        Schema::create('payment_types', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');

            $table->integer('gateway_type_id')->unsigned()->nullable();
            $table->foreign('gateway_type_id')
                ->references('id')->on('gateway_types')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });

        /**
         * Entities tables
         */
        Schema::create('tax_rates', function (Blueprint $table) {
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

            $table->string('name');

            $table->decimal('rate', 13, 3);
            $table->boolean('is_inclusive')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('products', function (Blueprint $table) {
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

            $table->string('name');
            $table->decimal('price', 13, 3);

            $table->integer('currency_id')->unsigned()->nullable();
            $table->foreign('currency_id')
                ->references('id')->on('currencies')
                ->onDelete('set null')->onUpdate('cascade');

            $table->decimal('qty', 13, 3)->nullable();
            $table->text('description')->nullable();

            $table->string('tax_rate_uuid')->nullable();
            $table->foreign('tax_rate_uuid')
                ->references('uuid')->on('tax_rates')
                ->onDelete('set null')->onUpdate('cascade');

            $table->timestamps();
            $table->timestamp('archived_at')->nullable();
            $table->softDeletes();
        });

        Schema::create('clients', function (Blueprint $table) {
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

            $table->string('name');

            $table->string('registration_number')->nullable();
            $table->string('vat_number')->nullable();

            $table->string('website')->nullable();
            $table->string('phone')->nullable();
            $table->text('description')->nullable();

            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();

            $table->integer('country_id')->unsigned()->nullable();
            $table->foreign('country_id')
                ->references('id')->on('countries')
                ->onDelete('set null')->onUpdate('cascade');

            $table->integer('currency_id')->unsigned()->nullable();
            $table->foreign('currency_id')
                ->references('id')->on('currencies')
                ->onDelete('set null')->onUpdate('cascade');

            $table->integer('language_id')->unsigned()->nullable();
            $table->foreign('language_id')
                ->references('id')->on('languages')
                ->onDelete('set null')->onUpdate('cascade');

            $table->integer('payment_terms')->nullable();

            $table->integer('company_size_id')->unsigned()->nullable();
            $table->foreign('company_size_id')
                ->references('id')->on('company_sizes')
                ->onDelete('set null')->onUpdate('cascade');

            $table->integer('industry_id')->unsigned()->nullable();
            $table->foreign('industry_id')
                ->references('id')->on('industries')
                ->onDelete('set null')->onUpdate('cascade');

            $table->timestamps();
            $table->timestamp('archived_at')->nullable();
            $table->softDeletes();
        });

        Schema::create('client_contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->unique();

            $table->string('client_uuid');
            $table->foreign('client_uuid')
                ->references('uuid')->on('clients')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->string('profile_uuid');
            $table->foreign('profile_uuid')
                ->references('uuid')->on('profiles')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });

        Schema::create('bills', function (Blueprint $table) {
            $table->increments('id');

            $table->string('number')->nullable();
            $table->string('po_number')->nullable();

            $table->decimal('partial', 13, 3)->default(0);
            $table->decimal('discount', 13, 3)->default(0);
            $table->enum('discount_type', \App\Domain\Constants\Bill\DiscountTypes::LIST);

            $table->date('date')->nullable();
            $table->date('due_date')->nullable();

            $table->integer('currency_id')->unsigned();
            $table->foreign('currency_id')
                ->references('id')->on('currencies')
                ->onDelete('restrict')->onUpdate('cascade');

            $table->text('notes')->nullable();
            $table->text('terms')->nullable();
            $table->text('footer')->nullable();

            $table->string('billable_type');
            $table->string('billable_uuid');

            $table->timestamps();
            $table->timestamp('archived_at')->nullable();
            $table->softDeletes();
        });

        Schema::create('bill_items', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('bill_id')->unsigned();
            $table->foreign('bill_id')
                ->references('id')->on('bills')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->string('product_uuid');
            $table->foreign('product_uuid')
                ->references('uuid')->on('products')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->decimal('cost', 13, 3);
            $table->decimal('qty', 13, 3);
            $table->decimal('discount', 10, 4)->nullable();

            $table->string('tax_rate_uuid')->nullable();
            $table->foreign('tax_rate_uuid')
                ->references('uuid')->on('tax_rates')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->integer('index');

            $table->timestamps();
        });

        Schema::create('recurring_invoices', function (Blueprint $table) {
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

            $table->string('client_uuid');
            $table->foreign('client_uuid')
                ->references('uuid')->on('clients')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->date('end_date')->nullable();
            $table->integer('due_date')->default(0);
            $table->integer('frequency_value')->default(1);
            $table->enum('frequency_type', \App\Domain\Constants\RecurringInvoice\FrequencyTypes::LIST)
                ->default(\App\Domain\Constants\RecurringInvoice\FrequencyTypes::WEEK);

            $table->boolean('autobill')->default(false);

            $table->enum('status', \App\Domain\Constants\RecurringInvoice\Statuses::LIST)
                ->default(\App\Domain\Constants\RecurringInvoice\Statuses::DRAFT);

            $table->timestamp('last_sent_at')->nullable();
            $table->timestamps();
            $table->timestamp('archived_at')->nullable();
            $table->softDeletes();
        });

        Schema::create('invoices', function (Blueprint $table) {
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

            $table->string('client_uuid');
            $table->foreign('client_uuid')
                ->references('uuid')->on('clients')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->string('invoiceable_type')->nullable();
            $table->string('invoiceable_uuid')->nullable();

            $table->enum('status', \App\Domain\Constants\Invoice\Statuses::LIST)
                ->default(\App\Domain\Constants\Invoice\Statuses::DRAFT);

            $table->timestamps();
            $table->timestamp('archived_at')->nullable();
            $table->softDeletes();
        });

        Schema::create('quotes', function (Blueprint $table) {
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

            $table->string('client_uuid');
            $table->foreign('client_uuid')
                ->references('uuid')->on('clients')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->string('invoice_uuid')->nullable();
            $table->foreign('invoice_uuid')
                ->references('uuid')->on('invoices')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->enum('status', \App\Domain\Constants\Quote\Statuses::LIST);

            $table->timestamps();
            $table->timestamp('archived_at')->nullable();
            $table->softDeletes();
        });

        Schema::create('credits', function (Blueprint $table) {
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

            $table->string('client_uuid');
            $table->foreign('client_uuid')
                ->references('uuid')->on('clients')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->decimal('amount', 13, 3);
            $table->decimal('balance', 13, 3);
            $table->date('credit_date');
            $table->string('credit_number')->nullable();

            $table->integer('currency_id')->unsigned();
            $table->foreign('currency_id')
                ->references('id')->on('currencies')
                ->onDelete('restrict')->onUpdate('cascade');

            $table->timestamps();
            $table->timestamp('archived_at')->nullable();
            $table->softDeletes();
        });

        Schema::create('expense_categories', function (Blueprint $table) {
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

            $table->string('name');

            $table->timestamps();
            $table->timestamp('archived_at')->nullable();
            $table->softDeletes();
        });

        Schema::create('vendors', function (Blueprint $table) {
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

            $table->string('company_name');
            $table->string('registration_number')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('website')->nullable();
            $table->string('phone')->nullable();
            $table->string('logo')->nullable();

            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('state')->nullable();

            $table->integer('country_id')->unsigned()->nullable();
            $table->foreign('country_id')
                ->references('id')->on('countries')
                ->onDelete('set null')->onUpdate('cascade');

            $table->integer('currency_id')->unsigned()->nullable();
            $table->foreign('currency_id')
                ->references('id')->on('currencies')
                ->onDelete('set null')->onUpdate('cascade');

            $table->timestamps();
            $table->timestamp('archived_at')->nullable();
            $table->softDeletes();
        });

        Schema::create('expenses', function (Blueprint $table) {
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

            $table->string('vendor_uuid');
            $table->foreign('vendor_uuid')
                ->references('uuid')->on('vendors')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->string('client_uuid');
            $table->foreign('client_uuid')
                ->references('uuid')->on('clients')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->string('expense_category_uuid')->nullable();
            $table->foreign('expense_category_uuid')
                ->references('uuid')->on('expense_categories')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->decimal('amount', 13, 3);

            $table->integer('currency_id')->unsigned()->nullable();
            $table->foreign('currency_id')
                ->references('id')->on('currencies')
                ->onDelete('set null')->onUpdate('cascade');

            $table->string('expensable_type')->nullable();
            $table->string('expensable_uuid')->nullable();

            $table->date('date')->nullable();

            $table->timestamps();
            $table->timestamp('archived_at')->nullable();
            $table->softDeletes();
        });

        Schema::create('payments', function (Blueprint $table) {
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

            $table->string('client_uuid');
            $table->foreign('client_uuid')
                ->references('uuid')->on('clients')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->string('invoice_uuid');
            $table->foreign('invoice_uuid')
                ->references('uuid')->on('invoices')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->decimal('amount', 13, 3);
            $table->decimal('refunded', 13, 3)->default(0);

            $table->integer('currency_id')->unsigned()->nullable();
            $table->foreign('currency_id')
                ->references('id')->on('currencies')
                ->onDelete('set null')->onUpdate('cascade');

            $table->integer('payment_type_id')->unsigned()->nullable();
            $table->foreign('payment_type_id')
                ->references('id')->on('payment_types')
                ->onDelete('set null')->onUpdate('cascade');

            $table->date('payment_date')->nullable();
            $table->string('payment_reference')->nullable();

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
        Schema::dropIfExists('tax_rates');
        Schema::dropIfExists('quotes');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('recurring_invoices');
        Schema::dropIfExists('bills');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('company_sizes');
        Schema::dropIfExists('products');
        Schema::dropIfExists('industries');
        Schema::dropIfExists('languages');
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('countries');
    }
}