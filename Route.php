<?php

namespace microsite\core;

interface Route {
	public function match($path);
}
