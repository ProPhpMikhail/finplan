<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager;

return new class extends Migration
{
    private const TABLE_NAME = 'trades';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Manager::schema()->create(self::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->char('pair', 8);
            $table->integer('orderId')->unique();
            $table->double('price');
            $table->double('from');
            $table->double('to');
            $table->bigInteger('type');
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
        Manager::schema()->drop(self::TABLE_NAME);
    }
};

