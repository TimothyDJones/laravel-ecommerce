<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAddressesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
                Schema::create('addresses', function(Blueprint $table) {
                        $table->increments('id');
			$table->string('addr1', 50);
			$table->string('addr2', 50)->nullable();
			$table->string('city', 30);
			$table->string('state', 10);
			$table->string('postal_code', 10);
                        $table->string('country', 10)->default('USA');  /* ISO 3-character country code */
                        $table->boolean('primary_addr_ind')->default(TRUE);
                        $table->unsignedInteger('customer_id')->nullable();                        
                        $table->foreign('customer_id')
                                ->references('id')
                                ->on('customers')
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
                Schema::dropIfExists('addresses');
                DB::statement('SET FOREIGN_KEY_CHECKS = 1');
	}

}
