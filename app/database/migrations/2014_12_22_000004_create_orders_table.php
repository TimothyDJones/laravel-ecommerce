<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
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
                Schema::dropIfExists('orders');
                DB::statement('SET FOREIGN_KEY_CHECKS = 1');
	}

}
