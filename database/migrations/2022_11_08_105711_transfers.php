<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLE_NAME = 'transfers';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->bigInteger('previous_id')->unsigned()->nullable();
            $table->bigInteger('from_account_id')->unsigned();
            $table->float('from_amount');
            $table->bigInteger('to_account_id')->unsigned();
            $table->float('to_amount');
            $table->float('rate');
            $table->timestamps();

            $table->foreign('previous_id')->references('id')->on('transfers');
            $table->foreign('from_account_id')->references('id')->on('accounts');
            $table->foreign('to_account_id')->references('id')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
};
