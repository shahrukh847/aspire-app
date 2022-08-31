<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{   
		// Roles
		DB::table('roles')->insert([
			[
				'user_type' => 'Admin'
			],
			[
				'user_type' => 'User'
			],
		]);
	}
}