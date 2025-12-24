<?php


namespace ElegantMedia\PHPToolkit;

use ElegantMedia\PHPToolkit\Exceptions\FileSystem\DirectoryMissingException;
use ElegantMedia\PHPToolkit\Exceptions\FIleSystem\DirectoryNotCreatedException;

class Dir
{

	/**
	 * Create a directory if it doesn't exist
	 *
	 * @param      $dirPath
	 * @param int $permissions
	 * @param bool $recursive
	 *
	 * @return bool
	 * @throws DirectoryNotCreatedException
	 */
	public static function makeDirectoryIfNotExists($dirPath, $permissions = 0775, $recursive = true): bool
	{
		if (is_dir($dirPath)) {
			return true;
		}

		if (!mkdir($dirPath, $permissions, $recursive) && !is_dir($dirPath)) {
			throw new DirectoryNotCreatedException(sprintf('Directory "%s" was not created', $dirPath));
		}

		if (is_dir($dirPath)) {
			return true;
		}

		return false;
	}

	/**
	 *
	 * Delete a directory
	 *
	 * @param $dirPath
	 * @return bool
	 * @throws DirectoryMissingException
	 */
	public static function deleteDirectory($dirPath): bool
	{
		$invalidDirecotry = !is_readable($dirPath);
		if ($invalidDirecotry) {
			throw new DirectoryMissingException(sprintf("Directory '%s' does not exist or is not readable", $dirPath));
		}

		$isFile = !is_dir($dirPath);
		if ($isFile) {
			return unlink($dirPath);
		}

		$isEmpty = count(scandir($dirPath)) === 0;
		if ($isEmpty) {
			return rmdir($dirPath);
		}

		foreach (scandir($dirPath) as $item) {
			if ($item == '.' || $item == '..') {
				continue;
			}

			if (!static::deleteDirectory($dirPath . DIRECTORY_SEPARATOR . $item)) {
				return false;
			}
		}

		return rmdir($dirPath);
	}

	/**
	 *
	 * Delete files in a directory by wildcard
	 *
	 * @param $dir
	 * @param $wildcard
	 * @return int
	 */
	public static function cleanDirectoryByWildcard($dir, $wildcard): int
	{
		// get the files
		$files = glob($dir.DIRECTORY_SEPARATOR.$wildcard);

		$count = 0;
		foreach ($files as $file) {
			if (is_file($file)) {
				unlink($file);
				$count++;
			}
		}

		return $count;
	}

	/**
	 *
	 * Delete files in a directory by it's file extensions
	 *
	 * @param $dir
	 * @param $extension
	 * @return false|int
	 */
	public static function cleanDirectoryByExtension($dir, $extension)
	{
		$dir = rtrim($dir, DIRECTORY_SEPARATOR);

		if (!is_dir($dir)) {
			return false;
		}

		if (empty($extension)) {
			return false;
		}

		return self::cleanDirectoryByWildcard($dir, "*.$extension");
	}
}
