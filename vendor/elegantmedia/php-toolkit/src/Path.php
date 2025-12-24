<?php


namespace ElegantMedia\PHPToolkit;

class Path
{

	/**
	 *
	 * Add a trailing slash to the end of a string (if it doesn't exist)
	 *
	 * @param $path
	 *
	 * @return string
	 */
	public static function withEndingSlash($path): string
	{
		return rtrim($path, '/') . '/';
	}

	/**
	 *
	 * Remove the trailing slash from a string (if exists).
	 *
	 * @param $path
	 *
	 * @return string
	 */
	public static function withoutEndingSlash($path): string
	{
		return rtrim($path, '/');
	}

	/**
	 *
	 * Remove the leading slash of a string (if exists).
	 *
	 * @param $path
	 *
	 * @return string
	 */
	public static function withoutStartingSlash($path): string
	{
		$firstChar = $path[0];
		$hasLeadingSlash = $firstChar === "/";
		if (!$hasLeadingSlash) {
			return $path;
		}

		return ltrim($path, $firstChar);
	}

	/**
	 *
	 * Add a leading slash to a string (if it doesn't exist)
	 *
	 * @param $path
	 *
	 * @return string
	 */
	public static function withStartingSlash($path): string
	{
		return '/' . ltrim($path, '/');
	}

	/**
	 *
	 * Remove dot segments from paths.
	 *
	 * @link https://tools.ietf.org/html/rfc3986#section-5.2.4
	 * @link https://stackoverflow.com/a/21486848/1234452
	 *
	 * @param $path
	 * @return string
	 */
	public static function canonical($path): string
	{
		$path = explode('/', $path);
		$stack = [];
		foreach ($path as $seg) {
			if ($seg == '..') {
				// Ignore this segment, remove last segment from stack
				array_pop($stack);
				continue;
			}

			if ($seg == '.') {
				// Ignore this segment
				continue;
			}

			$stack[] = $seg;
		}

		return implode('/', $stack);
	}
}
