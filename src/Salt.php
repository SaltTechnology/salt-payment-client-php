<?php
echo getcwd() . "\n";

require_once('Constants.php');
require_once('Autoloader.php');
spl_autoload_register('Autoloader::loader');