<?php

namespace App\Entities\Auth;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class AbilityCategory extends Model
{

	use Searchable;
	use HasSlug;

	protected $fillable = [
		'name',
		'default_abilities'
	];

	public function getSlugOptions(): SlugOptions
	{
		return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug');
	}

	public function toSearchableArray()
	{
		return [
			'name' => $this->name,
		];
	}

	public function abilities()
	{
        // Assuming the standard Ability model is used. If there's a specific class, we should use that.
		return $this->hasMany(\App\Entities\Auth\Ability::class);
	}
}
