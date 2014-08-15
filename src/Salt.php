<?php
require_once dirname( __FILE__ ) . '/Constants.php';
require_once dirname( __FILE__ ) . '/Autoloader.php';
spl_autoload_register( 'Autoloader::loader' );