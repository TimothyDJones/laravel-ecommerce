<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateScoutMeritBadgeRequirementTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
                Schema::create('scout_merit_badge_requirement', function(Blueprint $table) {
                        $table->increments('id');
                        $table->unsignedInteger('merit_badge_requirement_id');
                        $table->foreign('merit_badge_requirement_id')
                                ->references('id')
                                ->on('merit_badge_requirements')
                                ->onDelete('cascade')
                                ->onUpdate('cascade');
                        $table->unsignedInteger('scout_id');
                        $table->foreign('scout_id')
                                ->references('id')
                                ->on('scouts')
                                ->onDelete('cascade')
                                ->onUpdate('cascade');
                        $table->unsignedInteger('counselor_id');
                        $table->foreign('counselor_id')
                                ->references('id')
                                ->on('adults')
                                ->onDelete('cascade')
                                ->onUpdate('cascade');
                        $table->date('requirement_completed_date');
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
                Schema::dropIfExists('scout_merit_badge_requirement');
                DB::statement('SET FOREIGN_KEY_CHECKS = 1');
	}

}
