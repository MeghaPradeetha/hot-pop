<?php

namespace App\Entities\SubscriptionPlans;

use EMedia\Formation\Entities\GeneratesFields;
use ElegantMedia\SimpleRepository\Search\Eloquent\SearchableLike;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{

    use HasFactory;
    use SearchableLike;
    use GeneratesFields;

    protected $fillable = [
        'name',
        'description',
        'price',
        'plan_id'
    ];

    protected $searchable = [
        'name'
    ];

    protected $editable = [
        'name',
        'description',
        'price',
        'plan_id'
    ];

    /**
     *
     * Add any update only validation rules for this model
     *
     * @return array
     */
    public function getCreateRules()
    {
        return [
            'name'    => 'required',
            'price'   => 'required',
            'plan_id' => 'required',
        ];
    }

    /**
     *
     * Add any update only validation rules for this model
     *
     * @return array
     */
    public function getUpdateRules()
    {
        return [
            'name' => 'required',
        ];
    }

    /**
     *
     * Add any update only validation messations
     *
     * @return array
     */
    public function getCreateValidationMessages()
    {
        return [];
    }

    /**
     *
     * Add any update only validation messations
     *
     * @return array
     */
    public function getUpdateValidationMessages()
    {
        return [];
    }

}
