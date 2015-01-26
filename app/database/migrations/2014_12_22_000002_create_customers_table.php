<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('last_name', 30);
			$table->string('first_name', 30);
                        $table->string('telephone1', 20);
                        $table->string('telephone2', 20)->nullable();
                        $table->string('email', 50)->unique();
                        $table->string('password');
                        $table->boolean('email_sent_ind')->default(FALSE);
                        $table->boolean('admin_ind')->default(FALSE);
                        $table->string('remember_token')->nullable();
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
                Schema::dropIfExists('customers');                
                DB::statement('SET FOREIGN_KEY_CHECKS = 1');
	}

}
