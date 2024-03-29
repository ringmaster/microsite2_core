<?php

namespace microsite\core;

class BaseObject {

	public static function create() {
		$class = get_called_class();
		$args = func_get_args();
		$r_class = new \ReflectionClass($class);
		return $r_class->newInstanceArgs( $args );
	}

}
