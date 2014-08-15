<?php
require_once dirname( __FILE__ ) . '/../src/Salt.php';
require_once dirname( __FILE__ ) . '/TestAutoloader.php';
spl_autoload_register( 'TestAutoloader::loader' );