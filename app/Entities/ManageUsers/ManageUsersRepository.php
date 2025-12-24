<?php

namespace App\Entities\ManageUsers;

use App\Entities\BaseRepository;
use App\Models\User;

class ManageUsersRepository extends BaseRepository
{

	public function __construct(User $model)
	{
		parent::__construct($model);
	}

	public function checkUserRole($userid, $role)
    {
        $filter = $this->newSearchFilter(false);
        $filter
            ->where('id', $userid)
            ->whereHas('roles', function ($q) use ($role) {
                $q->whereIn('name', [$role]);
            });

        $data = $this->search($filter)->first();

        return $data;
    }

   
}
