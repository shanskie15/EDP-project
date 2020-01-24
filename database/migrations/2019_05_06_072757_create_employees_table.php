<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('employees', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('firstname');
			$table->string('middlename');
			$table->string('lastname');
			$table->string('email')->unique();
			$table->date('birth_date');
			$table->string('contact');
			$table->string('address');
			$table->float('salary',8,2);
			$table->enum('gender',['female','male']);
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
		Schema::dropIfExists('employees');
	}
}
