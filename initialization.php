<?php

session_start();

// external dependencies
require_once __DIR__ . '/vendor/autoload.php';

// settings
include_once __DIR__ . '/config.php';

// legacy settings - check tag 'nocache' in the git repository to see when
// did resources caching got disabled from the config file.
define('CACHE_RESOURCES', false);
define('CACHE_TTL', 0);

date_default_timezone_set(TIMEZONE);

// Twig initialization
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem(TPL_DIR);

$tpl_engine = new Twig_Environment($loader, array(
    'cache'            => ENV == 'prod' ? TPL_CACHE_DIR : false,
    'charset'          => 'utf-8',
    'strict_variables' => false,
    'autoescape'       => true
));

$tpl_engine->getExtension('core')->setTimezone(TIMEZONE);

// helpers
foreach (glob(__DIR__ . '/helpers/*.php') as $f) {
    require_once $f;
}
if (!$ROOT_URL) {
    $ROOT_URL = current_url_dir();
}
define('ROOT_URL', $ROOT_URL);
