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
