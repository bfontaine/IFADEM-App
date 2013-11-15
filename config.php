<?php
/**
 *
 * Configuration file for the IFADEM Web app.
 * =============
 *
 * Default values are good, the only setting you may want to change is WS_URL,
 * which defines the URL for the Web services.
 *
 * ---
 *
 * Current environment. Use 'dev' or 'prod'.
 * The only thing which changes is the templates caching. When 'dev' is
 * selected, templates are kept as-is, and when 'prod' is selected,
 * they're compiled into PHP, which may improve a little bit the loading
 * time.
 * Default: 'dev' */
define('ENV', 'dev');
/*
 * The root directory of the app (i.e. the directory where it's installed. This
 * is set automatically to the directory of this file, so you don't have to
 * change this).
 * Default: __DIR__ */
define('ROOT_DIR', __DIR__);
/*
 * === Web Services ===
 *
 * The Web services base URL.
 * Default: 'https://c2i.education.fr/ifademws/ws.php' */
define('WS_URL', 'https://c2i.education.fr/ifademws/ws.php');
/*
 *
 * === URLS ===
 *
 * The root for all URLs. For example, if the URL of the index of the Web app
 * is
 *      example.org/ifadem/index.php
 *
 * then the root is 'example.org/ifadem'. Don't add the trailing slash at the
 * end. If this is set to a falthy value, it's set automatically to the current
 * directory.
 * Default: false */
$ROOT_URL = false;
/*
 *
 * The base URL for each podcast feed. Note that all directories must exist.
 * Default: 'p' */
define('FEEDS_ROOT', 'p');
/*
 *
 * The base URL for each manifest (AppCache) file. See FEEDS_ROOT.
 * Default: 'p' */
define('MANIFESTS_ROOT', 'p');
/*
 *
 * The base URL for cached resources. See FEEDS_ROOT.
 * Default: 'resources' */
define('RESOURCES_CACHE_ROOT', 'resources');
/*
 *
 * === Localisation ===
 *
 * The default language of the website. This is used for localisation. See the
 * $langdir directory for a list of available languages.
 * Default: 'fr' */
define('LANG', 'fr');
/*
 * The timezone of the website. This is used for date/time.
 * Default: 'Europe/Paris' */
define('TIMEZONE', 'Europe/Paris');
/*
 *
 * The directory for language files.
 * Default: __DIR__.'/lang' */
define('LANG_DIR', __DIR__ . '/lang');
/*
 *
 * === Templates ===
 *
 * The directory for templates.
 *Default: __DIR__.'/tpl' */
define('TPL_DIR', __DIR__ . '/tpl');
/*
 *
 * The directory for templates caching.
 * Default: __DIR__.'/tpl/cache' */
define('TPL_CACHE_DIR', __DIR__ . '/tpl/cache');
/*
 *
 * === Users Data ===
 *
 * The file where users' data is stored. This is not sensitive data, so it
 * will be in plain json.
 * Default: __DIR__.'/usersdata.json' */
define('DATA_FILE', __DIR__ . '/usersdata.json');
/*
 **/
