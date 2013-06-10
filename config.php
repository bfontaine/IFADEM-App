<?php
/**
 *
 * Configuration file for the IFADEM Web app.
 * ---
 *
 * The session key (authentification key) used for Web services: */
define('WS_SESS_KEY', '1');
/*
 *
 * The root for all URLs. For example, if the URL of the index of the Web app
 * is
 *      example.org/ifadem/index.php
 *
 * then the root is 'example.org/ifadem'. Don't add the trailing slash at the
 * end. */
define('ROOT', 'ifadem-02.bfontaine.net');
/*
 *
 * The default language of the website. This is used for localisation. See the
 * $langdir directory for a list of available languages. */
define('LANG', 'fr');
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
 * The file where users' data is stored. This is not sensitive data, so it
 * will be in plain json (default: ./usersdata.json): */
define('DATA_FILE', __DIR__ . '/usersdata.json');
/*
 **/
