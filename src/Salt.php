<?php
require_once __DIR__ . '/Constants.php';
require_once __DIR__ . '/SplClassLoader.php';

$loader = new SplClassLoader( 'SALT', __DIR__ );
$loader->register();
