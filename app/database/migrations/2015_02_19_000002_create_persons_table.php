<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePersonsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
                Schema::create('persons', function(Blueprint $table) {
                        $table->increments('id');
                        $table->integer('bsa_id')->nullable();
                        $table->string('first_name');
                        $table->string('last_name');
                        $table->string('email_address');
                        $table->string('password');
                        $table->string('home_phone')->nullable();
                        $table->string('cell_phone')->nullable();
                        $table->unsignedInteger('address_id');
                        $table->foreign('address_id')
                                ->references('id')
                                ->on('addresses')
                                ->onDelete('cascade')
                                ->onUpdate('cascade');                        
                        $table->timestamps();
                });                
                
                // Re-enable FK constraints...  Just in case!  :)
                DB::statement('SET FOREIGN_KEY_CHECKS = 1');
                
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
                DB::statement('SET FOREIGN_KEY_CHECKS = 0');
                Schema::dropIfExists('persons');
                DB::statement('SET FOREIGN_KEY_CHECKS = 1');
	}

}
