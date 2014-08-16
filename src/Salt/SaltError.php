<?php
namespace Salt;
class SaltError extends Exception
{

    public function __construct($message) {
         parent::__construct($message);
    }

}