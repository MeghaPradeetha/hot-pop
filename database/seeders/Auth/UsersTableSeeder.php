<?php
namespace Database\Seeders\Auth;

use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Silber\Bouncer\BouncerFacade as Bouncer;

class UsersTableSeeder extends Seeder
{

	public function run()
	{
		if (app()->environment() !== 'production') {
			$this->seedTestUsers();
			$this->seedRegularUsers();
			$this->seedEmailVerifications();
		}
	}

	public function seedTestUsers()
	{
		$users = [
			[
				'name'	 => 'Peter Parker (REGULAR USER)',
				'email'	 => 'user@hotpop.com',
				'age' => 37,
				'location' => 'Melbourn',
				'gender' => 'Male',
				'subscription_plan' => 'Free Trial',
				'password' => bcrypt('12345678')
			],
			[
				'name'	 => 'Bruce Banner (ADMIN)',
				'email'	 => 'admin@hotpop.com',
				'age' => 30,
				'location' => 'Melbourn',
				'gender' => 'Male',
				'subscription_plan' => 'Free Trial',
				'password' => bcrypt('12345678')
			],
            // Removed other hardcoded Hotpop users for cleaner setup
		];

		foreach ($users as $data) {
			if (!$user = User::where('email', $data['email'])->first()) {
				$user = User::create($data);
			}
		}
	}

	public function seedRegularUsers()
	{
		$faker = Faker::create('en_AU');

		foreach(range(1, 5) as $index)
		{
			$user = User::create([
				'name' => $faker->firstName,
				'last_name' => $faker->lastName,
				'email' => $faker->email,
				'password' => bcrypt('12345678'),
			]);

            Bouncer::assign('users')->to($user);
		}
	}

	protected function seedEmailVerifications()
	{
		$users = User::whereIn('email', [
			'user@hotpop.com',
			'admin@hotpop.com',
		])->get();

		foreach ($users as $user) {
			$user->email_verified_at = now();
			$user->save();
		}
	}

}
