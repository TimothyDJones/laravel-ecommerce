<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdultsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
                Schema::create('adults', function(Blueprint $table) {
                        $table->increments('id');
                        $table->unsignedInteger('person_id');
                        $table->foreign('person_id')
                                ->references('id')
                                ->on('persons')
                                ->onDelete('cascade')
                                ->onUpdate('cascade');                        
                        $table->enum('scout_relationship', array('Mother', 'Father', 'Other', 'None'));
                        $table->unsignedInteger('troop_position_id')->nullable();
                        $table->foreign('troop_position_id')
                                ->references('id')
                                ->on('troop_position')
                                ->onDelete('cascade')
                                ->onUpdate('cascade');
                        $table->unsignedInteger('scout_id')->nullable();
                        $table->foreign('scout_id')
                                ->references('id')
                                ->on('scouts')
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
                Schema::dropIfExists('adults');
                DB::statement('SET FOREIGN_KEY_CHECKS = 1');
	}

}
