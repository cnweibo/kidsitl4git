<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMathsum2exercisesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mathsum2exercises', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('operand1');
			$table->integer('operand2');
			$table->unique(['operand1','operand2']);
			$table->integer('sumdata');
			$table->string('difficulty');
			
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('mathsum2exercises');
	}

}