<?php
require_once 'vendor/autoload.php';

// Launching MVC design pattern
$controller = new \Parser\controllers\MainController;
$controller->launchApp();