<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanRequestsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('loan_requests', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->bigInteger('card_user_id')->unsigned();
      $table->foreign('card_user_id')->references('id')->on('card_users')->onDelete('cascade');
      $table->double('amount');
      $table->double('monthly_interest');
      $table->integer('total_duration');
      $table->boolean('is_school_fees')->default(false);
      $table->timestamp('approved_at')->nullable();
      $table->bigInteger('approved_by')->unsigned()->nullable()->default(null);
      $table->foreign('approved_by')->references('id')->on('hardmean')->onDelete('cascade');
      // $table->string('approved_by_user_type')->nullable();
      $table->timestamp('paid_at')->nullable();
      $table->bigInteger('marked_paid_by')->unsigned()->nullable()->default(null);
      $table->foreign('marked_paid_by')->references('id')->on('hardmean')->onDelete('cascade');
      // $table->string('marked_paid_by_user_type')->nullable();
      $table->timestamp('reminded_at')->nullable();
      $table->boolean('is_fully_paid')->default(false);

      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('loan_requests');
  }
}
