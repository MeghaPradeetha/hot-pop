<?php


use ElegantMedia\PHPToolkit\Check;

if (!function_exists('validate_all_present')) {

	/**
	 *
	 * Check all values are !empty
	 * Throws an exception if at least one value is empty
	 *
	 */
	function validate_all_present()
	{
		$args = func_get_args();

		if (!Check::allPresent($args)) {
			$errorMessage = 'At least one required variable is not present.';

			// show the variable location on the debug mode
			if (getenv('APP_DEBUG') === 'true') {
				// show the argument location for easier debugging
				$backtrace = debug_backtrace();

				if (isset($backtrace[0])) {
					$args = $backtrace[0]['args'];

					foreach ($args as $i => $iValue) {
						if ($iValue === null) {
							$errorMessage .= ' Argument ' . ($i + 1) . ' is empty.';
						}
					}
				}
			}

			throw new InvalidArgumentException($errorMessage);
		}
	}
}
