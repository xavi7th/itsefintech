<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBleytWalletIdToDebitCardsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('debit_cards', function (Blueprint $table) {
      $table->date('bleyt_wallet_id')->nullable()->after('year');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('debit_cards', function (Blueprint $table) {
      $table->dropColumn('bleyt_wallet_id');
    });
  }
}
