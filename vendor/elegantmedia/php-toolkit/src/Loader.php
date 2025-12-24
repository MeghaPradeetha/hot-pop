<?php


namespace ElegantMedia\PHPToolkit;

class Loader
{

	/**
	 *
	 * Include all PHP files from a directory
	 *
	 * @param $dirPath
	 */
	public static function includeAllFilesFromDir($dirPath): void
	{
		$includedFiles = get_included_files();

		foreach (glob($dirPath . "/*.php") as $filename) {
			if (!in_array($filename, $includedFiles, true)) {
				include_once $filename;
			}
		}
	}

	/**
	 *
	 * Include all PHP files from a directory, recursively
	 *
	 * @param $dirPath
	 */
	public static function includeAllFilesFromDirRecursive($dirPath): void
	{
		// include files from current dir
		self::includeAllFilesFromDir($dirPath);

		/** @var \SplFileInfo $filename */
		$filtered = [];
		foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dirPath)) as $file) {
			if (!in_array($file->getPathname(), get_included_files(), true)) {
				if ($file->getExtension() === 'php') {
					$filtered[] = $file;
					include_once $file->getPathname();
				}
			}
		}
	}
}
