<?php

// Composer: "fzaninotto/faker": "v1.4.0"
use Faker\Factory as Faker;

class TasksTableSeeder extends Seeder {

	public function run()
	{
		/*$faker = Faker::create();

		foreach(range(1, 10) as $index)
		{
			Task::create([

			]);
		}*/
		$tasks = array(
			['name' => 'Task 1', 'slug' => 'task-1', 'project_id' => 1, 'description' => 'My first task', 'created_at' => new DateTime, 'updated_at' => new DateTime],
			['name' => 'Task 2', 'slug' => 'task-2', 'project_id' => 1, 'description' => 'My second task', 'created_at' => new DateTime, 'updated_at' => new DateTime],
			['name' => 'Task 3', 'slug' => 'task-3', 'project_id' => 1, 'description' => 'My third task', 'created_at' => new DateTime, 'updated_at' => new DateTime],
			['name' => 'Task 4', 'slug' => 'task-4', 'project_id' => 2, 'description' => 'My fourth task', 'created_at' => new DateTime, 'updated_at' => new DateTime],
			['name' => 'Task 5', 'slug' => 'task-5', 'project_id' => 2, 'description' => 'My fifth task', 'created_at' => new DateTime, 'updated_at' => new DateTime],
		);
		
		// Uncomment the below to run the seeder
		DB::table('tasks')->insert($tasks);
	}

}
