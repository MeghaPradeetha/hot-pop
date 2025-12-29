<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EMedia\Formation\Builder\Formation;
use App\Entities\SubscriptionPlans\SubscriptionPlansRepository;

class SubscriptionPlanController extends Controller
{

    // Uncomment this line if you're going to use Oxygen's Default Controller Methods

    protected $repo;

    public function __construct(SubscriptionPlansRepository $repo)
    {
        $this->repo = $repo;

        $this->resourceEntityName = 'Subscription Plan';
        $this->isDestroyAllowed = true;
    }

    protected function getResourcePrefix()
    {
        return 'manage.subscription-plan';
    }

    protected function getIndexRouteName($suffix = 'index'): string
    {
        return 'manage.subs-plans.index';
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
