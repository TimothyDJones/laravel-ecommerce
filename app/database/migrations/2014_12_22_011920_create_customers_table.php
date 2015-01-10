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
                
                Schema::create('orders', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('customer_id');
                        $table->foreign('customer_id')
                                ->references('id')
                                ->on('customers')
                                ->onDelete('cascade')
                                ->onUpdate('cascade');
			$table->date('order_date');
                        $table->enum('order_status', array('Created', 'Pending', 'Completed'))->default('Created');
                        $table->enum('delivery_terms', array('Pickup', 'Ship_CD', 'Ship_DVD', 'MP3_Only'))->default('Pickup');
                        $table->string('order_notes')->nullable();
			$table->float('subtotal_amt')->default(0.0);
			$table->float('order_total')->default(0.0);
			$table->float('discounts')->default(0.0);
			$table->timestamps();
		});
                
                Schema::create('order_items', function(Blueprint $table) {
                        $table->increments('id');
                        $table->unsignedInteger('product_id');
                        $table->foreign('product_id')
                                ->references('id')
                                ->on('products')
                                ->onDelete('cascade')
                                ->onUpdate('cascade');  
                        $table->unsignedInteger('order_id');
                        $table->foreign('order_id')
                                ->references('id')
                                ->on('orders')
                                ->onDelete('cascade')
                                ->onUpdate('cascade'); 
                        $table->unsignedInteger('qty')->default(1);
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
                Schema::dropIfExists('order_items');
                Schema::dropIfExists('orders');
                Schema::dropIfExists('addresses');
                Schema::dropIfExists('customers');                
                Schema::dropIfExists('products');
                DB::statement('SET FOREIGN_KEY_CHECKS = 1');
	}

}
