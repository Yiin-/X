<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPathToPdfFieldToInvoicesExpensesAndQuotesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pdfs', function (Blueprint $table) {
            $table->increments('id');

            $table->string('path_to_pdf')->nullable();

            $table->enum('status', \App\Domain\Constants\Pdf\Statuses::LIST)
                ->default(\App\Domain\Constants\Pdf\Statuses::PENDING);

            $table->string('pdfable_type');
            $table->string('pdfable_id');

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
        Schema::dropIfExists('pdfs');
    }
}
