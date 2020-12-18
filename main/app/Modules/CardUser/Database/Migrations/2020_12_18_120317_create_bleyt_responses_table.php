<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBleytResponsesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('bleyt_responses', function (Blueprint $table) {
      $table->id();
      $table->foreignId('card_user_id')->constrained('card_users')->nullable()->onDelete('cascade')->onUpdate('cascade');
      $table->string('bleyt_endpoint');
      $table->json('supplied_data')->nullable();
      $table->integer('bleyt_response_status')->nullable();
      $table->longText('bleyt_response_body')->nullable();
      $table->boolean('is_successful')->nullable();


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
    Schema::dropIfExists('bleyt_responses');
  }
}
