<?php

namespace App\Entities\EarlyAccesses;

use EMedia\Formation\Entities\GeneratesFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EarlyAccess extends Model
{

    use HasFactory;

	// Auto-generate UUIDs for new records
	// use \Hotpop\OxygenFoundation\Database\Eloquent\Traits\AssignsUuid;

	// Uncomment the following if you want to use slug generation
	// use \Spatie\Sluggable\HasSlug;

	/*
	public function getSlugOptions(): \Spatie\Sluggable\SlugOptions
	{
		return \Spatie\Sluggable\SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug');
	}
    */

    protected $table = 'early_access_data';

	protected $fillable = [
		'name',
        'email',
        'contact_number'
	];

	protected $searchable = [
		'name'
	];

	protected $editable = [
    	'name',
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
