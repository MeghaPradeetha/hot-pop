<?php


namespace ElegantMedia\PHPToolkit\Types;

class DataClass
{

	use HasAttributes;

	public function __construct(array $attributes = [])
	{
		$this->attributes = $attributes;
	}
}
