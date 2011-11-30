<?php

// MICROSITE LOADER
define('MICROSITE_PATH', dirname(dirname(__FILE__)));

include 'ClassLoader.php';
ClassLoader::Create('microsite\core', dirname(MICROSITE_PATH))->register();
ClassLoader::Create('app', MICROSITE_PATH)->register();

app\Config::instance(); // This initializes app\Config as the "Config" singleton.
microsite\core\Application::start();


