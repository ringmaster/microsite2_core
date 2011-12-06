<?php

namespace microsite\core;

class Application extends BaseObject {

	static $config;

	public static function start() {

		$routes = Config::get('routes');

		foreach($routes as $route) {
			if($route->match(self::get_path())) {
				$controller_class = $route->controller;
				$action = $route->action;

				$controller = new $controller_class();
				$request_method = strtolower( $_SERVER['REQUEST_METHOD'] );
				if ( method_exists( $controller, $action . '_' . $request_method ) ) {
					$action .= '_' . $request_method;
				}
				if ( method_exists( $controller, $action ) ) {
					$controller->$action($route);
				}
				else {
					throw new \Exception('Controller method "' . $action . '" does not exist.');
				}
				exit();
			}
		}
	}

	static public function get_path() {
		static $fullpath;
		if(empty($fullpath)) {
			$base_url = rtrim( dirname( self::script_name() ) );

			$start_url = isset($_SERVER['REQUEST_URI'])
				? $_SERVER['REQUEST_URI']
				: $_SERVER['SCRIPT_NAME'] . (isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '');

			if ( strpos( $start_url, '?' ) ) {
				list($start_url) = explode( '?', $start_url, 2 );
			}

			if ( '/' != $base_url ) {
				$start_url = str_replace( $base_url, '', $start_url );
			}

			$path = trim( $start_url, '/' );

			$fullpath = $path;
		}
		return $fullpath;
	}

	public static function script_name() {
		static $scriptname;
		switch ( true ) {
			case isset($scriptname):
				break;
			case isset($_SERVER['SCRIPT_NAME']):
				$scriptname = $_SERVER['SCRIPT_NAME'];
				break;
			case isset($_SERVER['PHP_SELF']):
				$scriptname = $_SERVER['PHP_SELF'];
				break;
			default:
				throw new \Exception('Could not determine script name.');
				die();
		}
		return $scriptname;
	}

	public static function get_config() {
		return self::$config;
	}
}

?>