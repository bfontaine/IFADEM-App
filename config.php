<?php
/**
 *
 * Configuration file for the IFADEM Web app.
 * ---
 *
 * The session key (authentification key) used for Web services: */
$sesskey = 1;
/*
 *
 * The root for all URLs. For example, if the URL of the index of the Web app
 * is
 *      example.org/ifadem/index.php
 *
 * then the root is 'example.org/ifadem'. Don't add the trailing slash at the
 * end. */
$root      = 'ifadem-02.bfontaine.net';
/*
 *
 * The default language of the website. This is used for localisation. See the
 * $langdir directory for a list of available languages. */
$lang      = 'fr';
/*
 *
 * The directory for language files (default: ./lang): */
$langdir   = __DIR__ . '/lang';
/*
 *
 * === Templates ===
 *
 *
 * The directory for templates (default: ./tpl): */
$tpldir    = __DIR__ . '/tpl';
/*
 *
 * The directory for templates caching (default: ./tpl/cache): */
$tplcachedir = __DIR__ . '/tpl/cache';
/*
 *
 * The file where users' data is stored. This is not sensitive data, so it
 * will be in plain json (default: ./usersdata.json): */
$datafile = __DIR__ . '/usersdata.json';
/*
 **/
