<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		// $this->call('UserTableSeeder');
		// $this->call('ProjectsTableSeeder');
		// $this->call('TasksTableSeeder');
                $this->call('CustomersTableSeeder');
                $this->call('ProductsTableSeeder');
                $this->call('SessionsTableSeeder');
	}

}
