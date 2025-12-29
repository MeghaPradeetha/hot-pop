<?php
namespace Database\Seeders\Auth;

use App\Models\User;
use Illuminate\Database\Seeder;
use Silber\Bouncer\BouncerFacade as Bouncer;

class UserRolesTableSeeder extends Seeder
{
	public function run()
	{
		if (!app()->environment('production')) {
			$this->seedUserRoles();
		}
	}

	protected function seedUserRoles()
	{
        // Simple assignment for known users
        $this->assignRole('user@hotpop.com', 'users');
        $this->assignRole('admin@hotpop.com', 'admins');
	}

    protected function assignRole($email, $role)
    {
        $user = User::where('email', $email)->first();
        if ($user) {
            Bouncer::assign($role)->to($user);
        }
    }
}
