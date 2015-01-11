<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSessionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
                Schema::create('sessions', function(Blueprint $table) {
                        $table->increments('id');
                        $table->string('session_title', 255);
                        $table->date('session_date');
                        $table->string('speaker_name', 255)->index();
                        $table->string('filename_base', 255);
                        $table->integer('filesize');
                        $table->time('session_duration');
                        $table->string('scripture_references', 2000)->nullable();
                        $table->string('session_description', 2000)->nullable();
                        $table->integer('num_downloads');
                        $table->date('last_download_date')->nullable();
                        $table->enum('category', array('C', 'S', 'M', 'O'))->index()->default('O');
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
                Schema::dropIfExists('sessions');
	}

}
