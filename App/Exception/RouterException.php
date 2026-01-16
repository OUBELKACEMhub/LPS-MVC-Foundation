<?php
namespace App\Exception;
use Exception;

class RouterException extends Exception {
    public function __construct($message="REQUEST_METHOD does not exist or No matching routes  ", $code = 404)
    {
        parent::__construct($message, $code);
    }
}