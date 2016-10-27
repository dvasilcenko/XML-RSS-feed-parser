<?php
namespace Parser\helpers;

class Logger
{
    function __construct() {
        $this->log('Starting logger...');
    }
    function log($msg) {
        print date('Y-m-d H:i:s') . " $msg\n";
    }
}