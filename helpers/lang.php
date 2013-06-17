<?php

$l10n_strings = array();
require_once LANG_DIR . '/' . strtolower(LANG) . '.php';

/**
 * Localisation helper. Takes a string, and, if it's available in the current
 * language, return its translated version. If it's not available, return
 * the unchanged string.
 **/
function _l($str, $lang=null) {
    global $l10n_strings;
    if (isset($l10n_strings[$str])) {
        return $l10n_strings[$str];
    }
    return $str;
}


/**
 * Return a standard lang code from the one returned by a call to the
 * Web services. For example, the standard code for 'fre' (French) is 'fr'.
 **/
function get_lang_code($c) {
    if ($c === 'fre') { return 'fr'; } // I haven't seen more codes right now
    return $c;
}

/**
 * Return a language name from the one returned by a call to the
 * Web services.
 **/
function get_lang_name($c) {
    if ($c === 'fre') { return 'français'; } // I haven't seen more codes right now
    return $c;
}
