<?php


namespace ElegantMedia\PHPToolkit;

class Text
{

	/**
	 *
	 * Get a block of text and split it into lines
	 *
	 * @param $text
	 * @param null $delimiters
	 * @return array
	 *
	 * @example
	 * one, two,   three
	 * four
	 * five
	 *
	 * Returns
	 * ['one', 'two', 'three', 'four', 'five']
	 *
	 */
	public static function textToArray($text, $delimiters = null): array
	{
		if (!$delimiters) {
			$delimiters = [PHP_EOL, ';', ','];
		}

		$lines = str_replace($delimiters, $delimiters[0], $text);

		$trimmedLines = array_map('trim', explode(PHP_EOL, $lines));

		// remove empty values
		$lines = array_filter($trimmedLines, function ($item) {
			return $item !== '';
		});

		return $lines;
	}

	/**
	 * Convert an 'existing_snake_case' to 'existing snake case'
	 *
	 * @param $string
	 * @return string
	 */
	public static function reverseSnake($string)
	{
		$string = str_replace('_', ' ', $string);

		return $string;
	}

	/**
	 * Generate a random string without any ambiguous characters
	 * @param int $length
	 * @return string
	 */
	public static function randomUnambiguous($length = 16): string
	{
		$pool = '23456789abcdefghkmnpqrstuvwxyz';

		return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
	}
}
