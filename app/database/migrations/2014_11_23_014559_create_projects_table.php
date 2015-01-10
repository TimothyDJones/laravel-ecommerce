<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProjectsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('projects', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('slug');
			$table->timestamps();
		});
		
		Schema::create('tasks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('project_id')->unsigned();
			$table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
			$table->string('name');
			$table->string('slug');
			$table->boolean('completed');
			$table->text('description');
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
		Schema::drop('tasks');
		Schema::drop('projects');
	}

}
