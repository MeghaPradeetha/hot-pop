<?php

namespace App\Entities\Filters;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Filter extends Model
{

    use HasFactory;

	// Auto-generate UUIDs for new records

	// Uncomment the following if you want to use slug generation
	// use \Spatie\Sluggable\HasSlug;

	/*
	public function getSlugOptions(): \Spatie\Sluggable\SlugOptions
	{
		return \Spatie\Sluggable\SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug');
	}
    */

	protected $fillable = [
		'user_id','relationship_type','preferences','children','min_age','max_age'
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
