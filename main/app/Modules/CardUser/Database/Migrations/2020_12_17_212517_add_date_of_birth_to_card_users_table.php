<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDateOfBirthToCardUsersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('card_users', function (Blueprint $table) {
      $table->date('date_of_birth')->nullable()->after('email');
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
      $table->dropColumn('date_of_birth');
    });
  }
}
