<?php
namespace Database\Seeders\Auth;

use Illuminate\Database\Seeder;
use Silber\Bouncer\Database\Role;

class RolesTableSeeder extends Seeder
{
	public function run()
	{
		$defaultRoles = [
			[
				'name' => 'users',
				'title' => 'Users',
			],
			[
				'name' => 'admins',
				'title' => 'Admins',
			],
			[
				'name' => 'super-admins',
				'title' => 'Super-Admins',
			],
			[
				'name' => 'developers',
				'title' => 'Developers',
			],
			[
				'name' => 'managers',
				'title' => 'Managers',
			],
		];

		foreach ($defaultRoles as $role) {
			Role::firstOrCreate(
				['name' => $role['name']],
				['title' => $role['title']]
			);
		}
	}
}
