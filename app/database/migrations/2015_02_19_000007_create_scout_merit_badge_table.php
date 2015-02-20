<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateScoutMeritBadgeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
                Schema::create('scout_merit_badge', function(Blueprint $table) {
                        $table->increments('id');
                        $table->foreign('merit_badge_id')
                                ->references('id')
                                ->on('merit_badge')
                                ->onDelete('cascade')
                                ->onUpdate('cascade');
                        $table->foreign('scout_id')
                                ->references('id')
                                ->on('scouts')
                                ->onDelete('cascade')
                                ->onUpdate('cascade');  
                        $table->foreign('counselor_id')
                                ->references('id')
                                ->on('adults')
                                ->onDelete('cascade')
                                ->onUpdate('cascade');
                        $table->date('merit_badge_start_date');
                        $table->date('merit_badge_completed_date');
                        $table->date('merit_badge_presented_date');
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
                Schema::dropIfExists('scout_merit_badge');
                DB::statement('SET FOREIGN_KEY_CHECKS = 1');
	}

}
