<?php

namespace GSB\App;

chdir(realpath(__DIR__.'/../app'));
if (session_status() == PHP_SESSION_NONE) {
    ini_set('session.auto_start', 0);
}
require_once '../vendor/autoload.php'; // composer autoloader

require_once 'app.php';
Run();
