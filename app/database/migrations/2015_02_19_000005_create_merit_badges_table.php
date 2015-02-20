<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMeritBadgesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
                Schema::create('merit_badges', function(Blueprint $table) {
                        $table->increments('id');
                        $table->string('merit_badge_name');
                        $table->integer('reqts_last_changed_year');
                        $table->boolean('eagle_reqd_ind')->default(FALSE);
                        $table->string('badge_image')->nullable();
                        $table->string('merit_badge_org_url')->nullable();
                        $table->unsignedInteger('primary_counselor_id')->nullable();
                        $table->foreign('primary_counselor_id')
                                ->references('id')
                                ->on('adults')
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
                Schema::dropIfExists('merit_badges');
                DB::statement('SET FOREIGN_KEY_CHECKS = 1');
	}

}
