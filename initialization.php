<?php

// external dependencies
require_once __DIR__ . '/vendor/autoload.php';

// settings
include_once __DIR__ . '/config.php';

// Twig initialization
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem($tpldir);

$tpl_engine = new Twig_Environment($loader, array(
    'cache'            => $tplcachedir,
    'charset'          => 'utf-8',
    'strict_variables' => true,
    'autoescape'       => true
));

// helpers
foreach (glob(__DIR__ . '/helpers/*.php') as $f) {
    require_once $f;
}
