<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
                Schema::create('products', function(Blueprint $table) {
                        $table->increments('id');
			$table->string('prod_code', 20)->index();
                        $table->smallInteger('workshop_year')->default(2015)->index();
                        $table->string('form_id', 10);
                        $table->enum('prod_type', array('SET', 'CD', 'DVD'))->default('CD');
                        $table->float('price');
                        $table->smallInteger('unit_count')->default(1);
                        $table->smallInteger('qty_printed')->default(0);
                        $table->string('session_title', 100);
                        $table->string('speaker_last_name', 50);
                        $table->string('speaker_first_name', 50);
                        $table->string('location', 20);
                        $table->enum('day', array('WE', 'TH', 'FR', 'SA'));
                        $table->time('time');
                        $table->boolean('available_ind')->default(TRUE);
                        $table->float('mp3_price')->default(3.0);
                        $table->boolean('mp3_free_ind')->default(FALSE);
                        $table->string('mp3_subdir_name', 50)->nullable();
                        $table->string('mp3_prev_subdir_name', 50)->nullable();
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
                Schema::dropIfExists('products');
                DB::statement('SET FOREIGN_KEY_CHECKS = 1');
	}

}
