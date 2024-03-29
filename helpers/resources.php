<?php

function isHTTP($u) {
    return strpos($u, 'http://') == 0;
}

function isHTTPS($u) {
    return strpos($u, 'https://') == 0;
}

// proxy function used to manage cached versions of resources
function cache_res($url, $root=true, $ttl=0) {
    if (!defined('CACHE_RESOURCES') || !CACHE_RESOURCES) {
        // TODO keep only the path, not the domain?
        return $url;
    }

    if ($ttl <= 0) {
        $ttl = defined('CACHE_TTL') ? CACHE_TTL : 2592000; // one month
    }

    if (strpos($url, '/') == 0) { // absolute path
        $u = $url;
    } else {

        $u = RESOURCES_CACHE_ROOT . '/';

        $url2 = $url;

        if (isHTTP($url2)) {
            $url2 = substr($url2, 7);
        } else if (isHTTPS($url2)) {
            $url2 = substr($url2, 8);
        }

        $parts = explode('/', $url2);

        $u .= $parts[count($parts)-1];

    }

    $exists = $u && file_exists($u);
    $t = $exists ? filemtime($u) : -1;

    if ($t <= 0 || time() - $t > $ttl) {

        if (!isHTTP($url) && !isHTTPS($url)) {
            $url = 'http://' . $url;
        }

        $data = file_get_contents($url);

        if ($data !== FALSE) {
            file_put_contents($u, $data);
        }

    }

    return $root ? (ROOT_URL . '/' . $u) : $u;
}


define('RESOURCES_METADATA', RESOURCES_CACHE_ROOT . '/_metadata.json');

/* Return resources metadata, with at least these keys:
 *
 * {
 *   42: {
 *     mp3s: [ { title: ..., url: ... }, ... ],
 *     title: ...,
 *     url: ...
 *   },
 *   ...
 * }
 *
 *
 * */
function get_resources_metadata() {
    if (!file_exists(RESOURCES_METADATA)) {
        file_put_contents(RESOURCES_METADATA, '{}');
    }

    $data = file_get_contents(RESOURCES_METADATA);

    if ($data) {
        return json_decode($data, true);
    }

}

// legacy function
function cache_mp3s_metadata($mp3s) {
    return $mp3s;
}

// legacy function
function cache_resources_metadata($ress) {
    return $ress;
}
