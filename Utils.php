<?php

namespace microsite\core;

class Utils
{
	public static function merge_arrays( $a1, $a2 ) {
		foreach ( $a2 as $key => $Value ) {
			if ( array_key_exists( $key, $a1 ) && is_array( $Value ) ) {
				$a1[$key] = Utils::merge_arrays( $a1[$key], $a2[$key] );
			}

			else {
				$a1[$key] = $Value;
			}
		}

		return $a1;
	}
}
