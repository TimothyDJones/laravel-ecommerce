<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMeritBadgeRequirementsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
                Schema::create('merit_badge_requirements', function(Blueprint $table) {
                        $table->increments('id');
                        $table->string('requirement_identifier');
                        $table->string('requirement_description', 2000);
                        $table->integer('requirement_year');
                        $table->integer('sort_order_number');
                        $table->unsignedInteger('merit_badge_id');
                        $table->foreign('merit_badge_id')
                                ->references('id')
                                ->on('adults')
                                ->onDelete('cascade')
                                ->onUpdate('cascade')
                                ->nullable();
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
                Schema::dropIfExists('merit_badge_requirements');
                DB::statement('SET FOREIGN_KEY_CHECKS = 1');
	}

}
