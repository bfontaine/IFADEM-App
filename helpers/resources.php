<?php

function isHTTP($u) {
    return strpos($u, 'http://') == 0;
}

function isHTTPS($u) {
    return strpos($u, 'https://') == 0;
}

// proxy function used to manage cached versions of resources
function cache_res($url, $root=true, $ttl=0) {
    if (!CACHE_RESOURCES) {
        return $url;
    }

    if ($ttl <= 0) {
        $ttl = CACHE_TTL;
    }

    if (strpos($url, '/') == 0) { // absolute path
        $u = $url;
    } else {

        $u = RESOURCES_CACHE_ROOT . '/';

        if (isHTTP($url)) {
            $url = substr($url, 7);
        } else if (isHTTPS($url)) {
            $url = substr($url, 8);
        }

        $parts = explode('/', $url);

        $u .= $parts[count($parts)-1];

    }

    $t = file_exists($u) ? filemtime($u) : -1;

    if ($t <= 0 || time() - $t > $ttl) {

        if (!isHTTP($url) && !isHTTPS($url)) {
            $url = 'http://' . $url;
        }

        // FIXME 'http://' is not prepended to $url
        $data = file_get_contents($url);

        if ($data !== FALSE) {
            file_put_contents($u, $data);
        }

    }

    return $root ? (ROOT_URL . '/' . $u) : $u;
}
