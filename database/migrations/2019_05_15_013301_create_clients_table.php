<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	public function up()
	{
		Schema::create('clients', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('store');
			$table->string('owner');
			$table->string('contact')->unique();
			$table->string('email')->unique();
			$table->string('location');
			$table->string('contact_person');
			$table->integer('division_id');
			$table->text('comments')->nullable();
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
			Schema::dropIfExists('clients');
	}
}
