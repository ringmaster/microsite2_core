<?php

namespace microsite\core;

abstract class Singleton
{
	// Single instance of class available.
	private static $instances = array();

	public static function instance()
	{
		$class = get_called_class();
		if ( ! isset( self::$instances[$class] ) ) {
			$r_class = new \ReflectionClass($class);
			self::$instances[$class] = $r_class->newInstanceArgs();
		}
		return self::$instances[$class];
	}

	/**
	 * Prevent instance construction and cloning
	 */
	public final function __construct() {}
	private final function __clone() {}
}