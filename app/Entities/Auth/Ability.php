<?php

namespace App\Entities\Auth;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Ability extends Model
{

	use HasSlug;

	protected $fillable = ['name', 'title'];

	public function getSlugOptions(): SlugOptions
	{
		return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('name')
									->slugsShouldBeNoLongerThan(150);
	}

	public function category()
	{
		return $this->belongsTo(AbilityCategory::class, 'ability_category_id');
	}
}
