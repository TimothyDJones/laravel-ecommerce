<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
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
                        $table->boolean('mp3_ind')->default(FALSE);
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
                DB::statement('SET FOREIGN_KEY_CHECKS = 1');
	}

}
