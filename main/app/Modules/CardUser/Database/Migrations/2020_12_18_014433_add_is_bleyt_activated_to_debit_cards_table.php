<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsBleytActivatedToDebitCardsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('debit_cards', function (Blueprint $table) {
      $table->boolean('is_bleyt_activated')->default(false)->after('bleyt_wallet_id');
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
      $table->dropColumn('is_bleyt_activated');
    });
  }
}
