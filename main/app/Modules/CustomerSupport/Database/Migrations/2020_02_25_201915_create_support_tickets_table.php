<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupportTicketsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('support_tickets', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('customer_support_id')->unsigned();
			$table->foreign('customer_support_id')->references('id')->on('customer_supports')->onDelete('cascade');
			$table->enum('ticket_type', ['complaint', 'request']);
			$table->enum('channel', ['phone call', 'email', 'sms', 'whatsapp', 'others']);
			$table->text('description');
			$table->bigInteger('department_id')->unsigned();
			$table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');

			$table->timestamp('assigned_at')->nullable();
			$table->bigInteger('assignee_id')->nullable();
			$table->string('assignee_type')->nullable();
			$table->timestamp('resolved_at')->nullable();
			$table->bigInteger('resolver_id')->nullable();
			$table->string('resolver_type')->nullable();


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
		Schema::dropIfExists('support_tickets');
	}
}
