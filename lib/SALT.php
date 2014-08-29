<?php

if (version_compare(PHP_VERSION, '5.3.0', '<')) {
    throw new Exception('PHP version >= 5.3.0 required');
}


date_default_timezone_set(date_default_timezone_get());

require_once __DIR__ . '/Constants.php';
require_once __DIR__ . '/SplClassLoader.php';

$loader = new SplClassLoader( 'SALT', __DIR__ );
$loader->register();

