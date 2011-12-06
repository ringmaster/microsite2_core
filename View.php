<?php

namespace microsite\core;

class View extends BaseObject {
	private $vars = array();

	public function __construct( $vars = array(), $template = null ) {
		$this->vars = $vars;
		if ( isset($template) ) {
			echo $this->render( $template );
		}
	}

	public function __get( $name ) {
		return $this->vars[$name];
	}

	public function __set( $name, $value ) {
		$this->vars[$name] = $value;
	}

	public static function fragment( $fragment ) {
		$view = new Dom($fragment);
		return $view;
	}

	public function get_views() {
		static $views;
		if(empty($views)) {
			$glob = glob( Config::get('paths/views') . '/*.php' );
			$views = array();
			foreach ( $glob as $view ) {
				$views[basename( $view, '.php' )] = $view;
			}
			//$views = Plugin::call( 'viewlist', $views );
		}
		return $views;
	}

	public function render( $viewname ) {
		$views = $this->get_views();

		if ( $viewname == '' ) {
			if ( !empty($this->vars['_view']) ) {
				$viewname = $this->vars['_view'];
			} else {
				$viewname = '404';
			}
		}
		if ( isset($views[$viewname]) ) {
			foreach ( $this->vars as $k => $v ) {
				if ( $k[0] != '_' ) {
					$$k = $v;
				}
			}
			$view = $this;
			ob_start();
			include $views[$viewname];
			$out = ob_get_clean();
			$out = new DOM($out);
			return $out;
		} else {
			var_dump($views);
			throw(new \Exception('View does not exist: ' . $viewname));
		}
	}
}

?>