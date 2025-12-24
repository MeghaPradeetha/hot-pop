<?php

namespace ElegantMedia\PHPToolkit;

class Arr
{


	/**
	 *
	 * Is the array an associative array?
	 *
	 * @param $input
	 *
	 * @return bool
	 */
	public static function isAssoc($input): bool
	{
		if (!is_array($input)) {
			return false;
		}
		return array_keys($input) !== range(0, count($input) - 1);
	}


	/**
	 *
	 * Find matching structure on a nested array recursively
	 *
	 * @param       $subset
	 * @param       $array
	 * @param array $results
	 *
	 * @return array
	 */
	public static function intersectRecursive($array, $subset, $results = []): array
	{
		$isAssocArray = self::isAssoc($subset);

		if ($isAssocArray) {
			// loop each row of array
			// iterating through parents
			foreach ($subset as $key => $value) {
				if ($key) {
					if (isset($array[$key])) {
						$filteredSource = $array[$key];

						//if the value is array, it will do the recursive
						if (is_array($value)) {
							$loopResults = self::intersectRecursive($filteredSource, $subset[$key], $results);
							$results[$key] = $loopResults;
						}
					}
				}
			}
		} else {
			// iterate through final leaf nodes
			foreach ($subset as $subsetRow) {
				foreach ($array as $sourceRow) {
					$subsetRow = self::removeChildArrays($subsetRow);
					$sourceRow = self::removeChildArrays($sourceRow);
					if (array_intersect($subsetRow, $sourceRow) == $subsetRow) {
						$results[] = $subsetRow;
					}
				}
			}
		}

		return $results;
	}


	public static function removeChildArrays($array): array
	{
		$response = [];
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$response[$key] = json_encode($value);
			} else {
				$response[$key] = $value;
			}
		}
		return $response;
	}

	/**
	 *
	 * Implode but don't include empty values
	 *
	 * @param $glue
	 * @param $pieces
	 *
	 * @return string
	 */
	public static function implodeIgnoreEmpty($glue, $pieces): string
	{
		// remove empty values
		$pieces = array_filter($pieces);

		return implode($glue, $pieces);
	}

	/**
	 *
	 * Replace an existing key of an array with a new one, recursively if required.
	 *
	 * @param array $array
	 * @param $existingKey
	 * @param $newKey
	 * @param false $recursive
	 * @return array
	 */
	public static function swapKey(array $array, $existingKey, $newKey, $recursive = false): array
	{
		$allArrayData = [];
		foreach ($array as $item) {
			$arrayData = $item;
			if (array_key_exists($existingKey, $arrayData)) {
				$arrayData[$newKey] = $arrayData[$existingKey];
				unset($arrayData[$existingKey]);
			}

			// do this recursively
			if ($recursive) {
				if (isset($arrayData[$newKey]) && count($arrayData[$newKey])) {
					$arrayData[$newKey] = self::swapKey($arrayData[$newKey], $existingKey, $newKey, true);
				}
			}

			$allArrayData[] = $arrayData;
		}
		return $allArrayData;
	}

	/**
	 * Replace keys of a given array based on a given function
	 * Based on http://stackoverflow.com/questions/1444484/how-to-convert-all-keys-in-a-multi-dimenional-array-to-snake-case
	 *
	 * @param mixed $mixed
	 * @param callable $keyReplaceFunction
	 * @param bool|true $recursive
	 */
	public static function swapKeysFn(&$mixed, callable $keyReplaceFunction, $recursive = true): void
	{
		if (is_array($mixed)) {
			foreach (array_keys($mixed) as $key) :
				# Working with references here to avoid copying the value,
				# Since input data can be large
				$value = &$mixed[$key];
				unset($mixed[$key]);

				#  - camelCase to snake_case
				$transformedKey = $keyReplaceFunction($key);

				# Work recursively
				if ($recursive && is_array($value)) {
					array_keys_replace($value, $keyReplaceFunction, $recursive);
				}

				# Store with new key
				$mixed[$transformedKey] = $value;
				# Do not forget to unset references!
				unset($value);
			endforeach;
		} else {
			$newVal = preg_replace('/[A-Z]/', '_$0', $mixed);
			$newVal = strtolower($newVal);
			$newVal = ltrim($newVal, '_');
			$mixed = $newVal;
			unset($newVal);
		}
	}


	/**
	 *
	 * Get an array and key it by a given key
	 * @example
	 * [
	 *        [ 'name' => 'john', 'age' => 45 ],
	 *        [ 'name' => 'jane', 'age', => 32 ],
	 * ]
	 * $people = Arr::keyBy($rows, 'name');
	 *
	 * becomes
	 * [
	 *        'john' => [ 'age' => 45 ],
	 *        'jane' => [ 'age' => 32 ],
	 * ]
	 *
	 *
	 * @param $array
	 * @param $keyBy
	 *
	 * @return array
	 */
	public static function keyBy($array, $keyBy): array
	{
		$newValues = [];

		foreach ($array as $key => $value) {
			if (is_array($value)) {
				if (isset($value[$keyBy]) && $value[$keyBy] !== '') {
					$newValues[$value[$keyBy]][] = $value;
				}
			}
		}

		return $newValues;
	}
}
