<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->date('delivery_date');
			$table->integer('num_products')->default('0');
			$table->float('total_amount',8,2)->default('0');
			$table->float('balance',8,2)->default('0');
			$table->enum('status',['shipped','received','pending'])->default('pending');
			$table->enum('paid',['unpaid','full','loan'])->default('unpaid');
			$table->integer('client_id');
			$table->text('comment')->nullable();
			$table->enum('new',['0','1'])->default('0');
			$table->enum('deleted',['0','1'])->default('0');
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
		Schema::dropIfExists('orders');
	}
}
