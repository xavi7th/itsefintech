<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBleytCustomerIdToCardUsersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('card_users', function (Blueprint $table) {
      $table->string('bleyt_customer_id')->nullable()->after('remember_token');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('card_users', function (Blueprint $table) {
      $table->dropColumn('bleyt_customer_id');
    });
  }
}
