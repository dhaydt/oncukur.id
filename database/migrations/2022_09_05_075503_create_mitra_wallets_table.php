<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMitraWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mitra_wallets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('mitra_id');
            $table->double('total_earning')->default(0);
            $table->double('withdrawn')->default(0);
            $table->double('comission_given')->default(0);
            $table->double('pending_withdraw')->default(0);
            $table->double('delivery_charge_earned')->default(0);
            $table->double('collected_cash')->default(0);
            $table->double('total_tax_collected')->default(0);
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
        Schema::dropIfExists('mitra_wallets');
    }
}
