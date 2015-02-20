<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePersonAddressesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
                Schema::create('person_addresses', function(Blueprint $table) {
                        $table->increments('id');
			$table->string('addr1', 50);
			$table->string('addr2', 50)->nullable();
			$table->string('city', 30);
			$table->string('state', 10);
			$table->string('postal_code', 10);
                        $table->string('country', 10)->default('USA');  /* ISO 3-character country code */
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
                Schema::dropIfExists('addresses');
                DB::statement('SET FOREIGN_KEY_CHECKS = 1');
	}

}
