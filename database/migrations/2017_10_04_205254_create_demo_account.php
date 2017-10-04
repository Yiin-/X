<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDemoAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app(App\Domain\Service\User\AccountService::class)->createNewAccount(
            'Loneland', 'admin@overseer.io', 'loneland', 'John', 'Doe', 'john.doe@example.com', 'admin'
        );
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
