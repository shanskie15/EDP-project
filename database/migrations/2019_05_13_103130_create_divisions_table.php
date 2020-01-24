<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDivisionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('divisions', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('name');
			$table->string('area');
			$table->integer('population')->default('0');
			$table->integer('agent_id');
			$table->enum('deleted',['0','1']);
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
			Schema::dropIfExists('divisions');
	}
}
