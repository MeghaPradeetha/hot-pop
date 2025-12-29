<?php

namespace App\Entities\InterestLists;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterestList extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'status'
    ];

    protected $searchable = [
        'name'
    ];

    protected $editable = [
        'name',
        [
            'name'    => 'status',
            'type'    => 'select',
            'options' => [
                'active'   => 'Active',
                'inactive' => 'Inactive',
            ]
        ]
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
            'name' => 'required',
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
