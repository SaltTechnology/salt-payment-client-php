<?php
class TestAutoloader {
    public static function loader( $class ) {
        $file = dirname( __FILE__ ) . '/tests/' . $class . '.php';;
        if ( !file_exists( $file ) ) {
            throw new Exception( "File Not Found: Unable to load $class." );
        }
        require_once $file;
    }
}
