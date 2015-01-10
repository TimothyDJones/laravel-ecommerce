<?php

// Composer: "fzaninotto/faker": "v1.4.0"
use Faker\Factory as Faker;

class ProjectsTableSeeder extends Seeder {

	public function run()
	{
		/*$faker = Faker::create();

		foreach(range(1, 10) as $index)
		{
			Project::create([

			]);
		}*/
		
		$projects = array(			
			['name' => 'Project 1', 'slug' => 'project-1', 'created_at' => new DateTime, 'updated_at' => new DateTime],
			['name' => 'Project 2', 'slug' => 'project-2', 'created_at' => new DateTime, 'updated_at' => new DateTime],
			['name' => 'Project 3', 'slug' => 'project-3', 'created_at' => new DateTime, 'updated_at' => new DateTime],
		); 
		
		// Uncomment the below to run the seeder
		DB::table('projects')->insert($projects);
	}

}
