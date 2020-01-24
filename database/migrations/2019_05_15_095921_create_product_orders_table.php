<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
			Schema::create('product_orders', function (Blueprint $table) {
				$table->bigIncrements('id');
				$table->float('subtotal',8,2);
				$table->integer('quantity');
				$table->integer('product_id');
        $table->integer('order_id');
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
      Schema::dropIfExists('product_orders');
    }
}
