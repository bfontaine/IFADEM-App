<?php
/**
 *
 * Configuration file for the IFADEM Web app.
 * ---
 *
 * Current environment. Use 'dev' or 'prod': */
define('ENV', 'dev');
/*
 * The root directory of the app. */
define('ROOT_DIR', __DIR__);
/*
 * === Web Services ===
 *
 * The Web services base URL: */
define('WS_URL', 'https://c2i.education.fr/ifademws/ws.php');
/*
 *
 * === URLS ===
 *
 *
 * The root for all URLs. For example, if the URL of the index of the Web app
 * is
 *      example.org/ifadem/index.php
 *
 * then the root is 'example.org/ifadem'. Don't add the trailing slash at the
 * end. */
define('ROOT_URL', 'ifadem.bfontaine.net');
/*
 *
 * The base URL for each podcast feed. Note that all directories must exist. */
define('FEEDS_ROOT', 'p');
/*
 *
 * The base URL for each manifest (AppCache) file. See FEEDS_ROOT. */
define('MANIFESTS_ROOT', 'p');
/*
 * 
 * === Localisation ===
 *
 *
 * The default language of the website. This is used for localisation. See the
 * $langdir directory for a list of available languages. */
define('LANG', 'fr');
/*
 * The timezone of the website. This is used for date/time. */
define('TIMEZONE', 'Europe/Paris');
/*
 *
 * The directory for language files (default: ./lang): */
define('LANG_DIR', __DIR__ . '/lang');
/*
 *
 * === Templates ===
 *
 *
 * The directory for templates (default: ./tpl): */
define('TPL_DIR', __DIR__ . '/tpl');
/*
 *
 * The directory for templates caching (default: ./tpl/cache): */
define('TPL_CACHE_DIR', __DIR__ . '/tpl/cache');
/*
 *
 * === DATA ===
 *
 *
 * The file where users' data is stored. This is not sensitive data, so it
 * will be in plain json (default: ./usersdata.json): */
define('DATA_FILE', __DIR__ . '/usersdata.json');
/*
 **/
