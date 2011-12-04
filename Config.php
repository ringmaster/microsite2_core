<?php

namespace microsite\core;

class Config extends Singleton {
	protected $data = false;

	const MICROSITE_CONFIG = '/app/config.php';

	public static function load( $load = null ) {
		$config = Config::instance();

		$replacements = array(
			'#{MICROSITE_PATH}#' => MICROSITE_PATH,
			'#{MICROSITE_CONFIG}#' => MICROSITE_PATH . self::MICROSITE_CONFIG,
			'#{MICROSITE_CONFIG_PATH}#' => dirname( MICROSITE_PATH . self::MICROSITE_CONFIG ),
		);

		// Some decent default config
		if(!$config->data) {
			$data = array(
				'paths' => array(
					'views' => '{MICROSITE_PATH}/app/views',
					'controllers' => '{MICROSITE_PATH}/app/controllers',
				)
			);
			$config->data = Config::replace($data, $replacements);
		}

		if ( empty($load) ) {
			$load = MICROSITE_PATH . self::MICROSITE_CONFIG;
		}

		$data = false;
		if ( !is_null( $load ) ) {
			if(is_string($load) && file_exists( $load ) ) {
				preg_match('#\.(\w+)$#', $load, $matches);
				switch(strtolower($matches[1])) {
					case 'ini':
						$data = parse_ini_file( $load, true );
						break;
					default:
						include $load;
				}

			}
			if(is_array( $load )) {
				$data = $load;
			}
		}

		if($data) {
			$data = Config::replace( $data, $replacements );
			$config->data = Utils::merge_arrays($config->data, $data);
		}
	}

	protected static function replace( $array, $replacements ) {
		$out = array();
		foreach ( $array as $key => $value ) {
			if ( is_array( $value ) ) {
				$out[$key] = Config::replace( $value, $replacements );
			}
			elseif( is_string( $value ) ) {
				$out[$key] = preg_replace( array_keys( $replacements ), array_values( $replacements ), $value );
			}
			else {
				$out[$key] = $value;
			}
		}
		return $out;
	}

	public static function get( $key = null, $default = null ) {
		$config = Config::instance();

		if ( !is_array( $config->data ) ) {
			self::load();
		}
		if ( is_scalar( $key ) ) {
			$key = explode( '/', $key );
		}
		else {
			$key = array();
		}
		$data = & $config->data;
		foreach ( $key as $keys ) {
			if ( isset($data[$keys]) ) {
				$data = & $data[$keys];
			} else {
				return $default;
			}
		}
		return $data;
	}

	public static function __static() {

	}
}
