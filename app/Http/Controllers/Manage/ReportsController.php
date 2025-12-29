<?php

namespace App\Http\Controllers\Manage;

use App\Entities\Reports\ReportsRepository;
use App\Http\Controllers\Controller;
use EMedia\Formation\Builder\Formation;

class ReportsController extends Controller
{


	// Uncomment this line if you're going to use Oxygen's Default Controller Methods

	protected $repo;

	public function __construct(ReportsRepository $repo)
	{
		$this->repo = $repo;

		$this->resourceEntityName = 'Report';
        $this->isDestroyAllowed = true;
	}

    protected function getResourcePrefix()
    {
        return 'manage.reports';
    }

	protected function getIndexRouteName($suffix = 'index'): string
	{
		return 'manage.reports.index';
	}

    /**
     *
     * This is the form shown when creating a new record.
     *
     * @param null $entity
     *
     * @return Formation
     */
    protected function getCreateForm($entity = null)
    {
        return new Formation($entity);
    }

    /**
     *
     * This is the form shown when editing an existing record.
     *
     * @param null $entity
     *
     * @return Formation
     */
    protected function getEditForm($entity = null)
    {
        return new Formation($entity);
    }

}
