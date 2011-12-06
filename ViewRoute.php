<?php

namespace microsite\core;

class ViewRoute extends BaseObject implements Route {
	public $controller;
	public $action;
	public $view;
	public $regex;
	public $params;

	public function __construct($view = '', $regex = '', $params = array()) {
		$this->controller = $this;
		$this->view = $view;
		$this->action = 'show_view';
		$this->regex = $regex;
		$this->params = $params;
	}

	public function match($path) {
		if(empty($this->regex)) return false;
		if(preg_match($this->regex, $path, $matches)) {
			$this->params = array_merge($matches, $this->params);
			return true;
		}
		return false;
	}

	public function show_view($route) {
		View::create($route->params, $route->view);
	}


}

