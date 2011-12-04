<?php

// MICROSITE LOADER
define('MICROSITE_PATH', dirname(dirname(__FILE__)));

include 'ClassLoader.php';
ClassLoader::Create('microsite\core', dirname(MICROSITE_PATH))->register();
ClassLoader::Create('app', MICROSITE_PATH)->register();

microsite\core\Config::load();
microsite\core\Application::start();


