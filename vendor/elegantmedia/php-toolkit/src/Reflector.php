<?php


namespace ElegantMedia\PHPToolkit;

class Reflector
{

	/**
	 *
	 * Get the inherited class' directory path
	 *
	 * @param object|string $self
	 * @param null $pathSuffix
	 * @return string
	 * @throws \ReflectionException
	 */
	public static function classPath($self, $pathSuffix = null): string
	{
		$class = $self;

		if (is_object($self)) {
			$class = get_class($self);
		}

		$reflector = new \ReflectionClass($class);

		$path = dirname($reflector->getFileName());

		if ($pathSuffix) {
			$path .= DIRECTORY_SEPARATOR.ltrim($pathSuffix, DIRECTORY_SEPARATOR);
		}

		return Path::canonical($path);
	}
}
