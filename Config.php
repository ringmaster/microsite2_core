<?php

namespace microsite\core;

class Config extends Singleton {
	protected $data = false;

	const MICROSITE_CONFIG = '/app/config.ini';

	public function load( $filename = null ) {
		if ( empty($filename) ) {
			$filename = MICROSITE_PATH . self::MICROSITE_CONFIG;
		}

		if ( !is_null( $filename ) && file_exists( $filename ) ) {
			$this->data = parse_ini_file( $filename, true );
		}
		else {
			$this->data = array();
		}

		// Some decent default config
		$this->data = $this->merge_arrays($this->data, array('paths' => array(
				'views' => '{MICROSITE_PATH}/app/views',
				'controllers' => '{MICROSITE_PATH}/app/controllers',
			)
		));

		$replacements = array(
			'#{MICROSITE_PATH}#' => MICROSITE_PATH,
			'#{MICROSITE_CONFIG}#' => MICROSITE_PATH . self::MICROSITE_CONFIG,
			'#{MICROSITE_CONFIG_PATH}#' => dirname( MICROSITE_PATH . self::MICROSITE_CONFIG ),
		);

		$this->data = $this->replace( $this->data, $replacements );
	}

	protected function merge_arrays( $a1, $a2 ) {
		foreach ( $a2 as $key => $Value ) {
			if ( array_key_exists( $key, $a1 ) && is_array( $Value ) ) {
				$a1[$key] = $this->merge_arrays( $a1[$key], $a2[$key] );
			}
			else {
				$a1[$key] = $Value;
			}
		}
		return $a1;
	}

	protected function replace( $array, $replacements ) {
		$out = array();
		foreach ( $array as $key => $value ) {
			if ( is_array( $value ) ) {
				$out[$key] = $this->replace( $value, $replacements );
			} else {
				$out[$key] = preg_replace( array_keys( $replacements ), array_values( $replacements ), $value );
			}
		}
		return $out;
	}

	public function get( $key, $default = null ) {
		if ( !is_array( $this->data ) ) {
			self::load();
		}
		if ( is_scalar( $key ) ) {
			$key = explode( '/', $key );
		}
		$data = & $this->data;
		foreach ( $key as $keys ) {
			if ( isset($data[$keys]) ) {
				$data = & $data[$keys];
			} else {
				return $default;
			}
		}
		return $data;
	}

	public function get_routes() {
		return array(
			'hello' => new RegexRoute('app\controllers\Index', 'hello', '#^hello(?:/(?P<name>.+))?#'),
			'default' => new RegexRoute('app\controllers\Index', 'index', '#.*#'),
		);
	}
}
