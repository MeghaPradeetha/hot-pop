<?php
namespace Database\Seeders\Auth;

use Illuminate\Database\Seeder;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        $userModel = User::class;

        $adminData = [
            'name'     => 'Super Admin',
            'email'    => 'admin@hotpop.com',
            'password' => bcrypt('password'),
            'age' => 30, // Default age
            'location' => 'System',
            'gender' => 'Male',
        ];

        // Check if user exists
        $user = $userModel::where('email', $adminData['email'])->first();

        if (!$user) {
            $user = $userModel::create($adminData);
        }

        // Assign super-admins role
        $user->assign('super-admins');
        
        $this->command->info('Super Admin user created/updated: admin@hotpop.com / password');
    }
}
