<?php

/**
 * Return the current URL
 **/
function current_url() {
    $https = array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS']=='on';
    $u  = 'http' . ($https ? 's' : '') . '://';
    $u .= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    return $u;
}

/**
 * Return the current URL without the name of the file, e.g.:
 *  if the current URL is http://foo.com/toto.html -> http://foo.com
 *                        http://a.com/t/e/q.html  -> http://a.com/t/e
 **/
function current_url_dir() {
    $u = current_url(); 
    $u = explode('?', $u);
    $u = $u[0];
    return implode('/', explode('/', $u, -1));
}
