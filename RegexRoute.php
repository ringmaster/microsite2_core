<?php

namespace microsite\core;

class RegexRoute extends BaseObject implements Route {
	public $controller;
	public $action;
	public $regex;
	public $params;

	public function __construct($controller, $action, $regex, $params = array()) {
		$this->controller = $controller;
		$this->action = $action;
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

}
