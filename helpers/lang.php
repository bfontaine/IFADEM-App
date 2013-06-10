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

