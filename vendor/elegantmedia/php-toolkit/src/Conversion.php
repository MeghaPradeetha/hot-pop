<?php

namespace ElegantMedia\PHPToolkit;

class Conversion
{

	/**
	 *
	 * Convert bytes to a human readable format.
	 *
	 * @link http://codeaid.net/php/convert-size-in-bytes-to-a-human-readable-format-(php)
	 *
	 * @param int $bytes
	 * @param int $precision
	 *
	 * @return string
	 */
	public static function bytesToHumans(int $bytes, $precision = 2): ?string
	{
		$kilobyte = 1024;
		$megabyte = 1048576; 		// $kilobyte * 1024;
		$gigabyte = 1073741824; 	// $megabyte * 1024;
		$terabyte = 1099511627776; 	// $gigabyte * 1024;

		if (($bytes >= 0) && ($bytes < $kilobyte)) {
			return $bytes . ' B';
		} elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
			return round($bytes / $kilobyte, $precision) . ' KB';
		} elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
			return round($bytes / $megabyte, $precision) . ' MB';
		} elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
			return round($bytes / $gigabyte, $precision) . ' GB';
		} elseif ($bytes >= $terabyte) {
			return round($bytes / $terabyte, $precision) . ' TB';
		} else {
			return $bytes . ' B';
		}
	}


	/**
	 * Converts a string with numbers to a full number
	 *
	 * @param $string
	 * @return int
	 *
	 */
	public static function stringToInt($string): int
	{
		return (int) round(floatval(preg_replace("/[^0-9.]/", "", $string)));
	}

	/**
	 *
	 * Convert a string numeric to a float
	 * Eg: 1,232.12 -> becomes -> (float) 1232.12
	 *
	 * @param $value
	 *
	 * @return float
	 */
	public static function stringToFloat($value): float
	{
		return (float) (filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));
	}
}
