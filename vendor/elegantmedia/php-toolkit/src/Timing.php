<?php

namespace ElegantMedia\PHPToolkit;

class Timing
{

	/**
	 * Get the micro-timestamp of current time
	 *
	 * @return string
	 */
	public static function microTimestamp()
	{
		// get microtime of file generation to preserve migration sequence
		$time = explode(" ", microtime());

		// change the format to 2008_07_14_010813.98232
		return date("Y_m_d_His", $time[1]) . '.' . substr((string)$time[0], 2, 5);
	}
}
