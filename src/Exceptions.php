<?php

// SALT Client API for PHP >= 5.3

error_reporting(E_ALL);

set_error_handler('exceptions_error_handler');

function exceptions_error_handler($severity, $message, $filename, $lineno) {
  if (error_reporting() == 0) {
    return;
  }
  if (error_reporting() & $severity) {
    throw new ErrorException($message, 0, $severity, $filename, $lineno);
  }
}


ini_set('display_errors',1);
error_reporting(-1);
?>
