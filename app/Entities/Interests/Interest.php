<?php

namespace App\Entities\Interests;

use EMedia\Formation\Entities\GeneratesFields;
use ElegantMedia\SimpleRepository\Search\Eloquent\SearchableLike;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{

    use HasFactory;
	use SearchableLike;
	use GeneratesFields;

	// Auto-generate UUIDs for new records
	// use \ElegantMedia\OxygenFoundation\Database\Eloquent\Traits\AssignsUuid;

	// Uncomment the following if you want to use slug generation
	// use \Spatie\Sluggable\HasSlug;

	/*
	public function getSlugOptions(): \Spatie\Sluggable\SlugOptions
	{
		return \Spatie\Sluggable\SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug');
	}
    */

	protected $fillable = [
		'user_id',
		'interests'
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

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

}
