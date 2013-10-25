<?php

// proxy function used to manage cached versions of resources
function cache_res($url, $root=true) {
    if (!CACHE_RESOURCES) {
        return $url;
    }

    $u = RESOURCES_CACHE_ROOT . '/';

    if (strpos($url, 'http://') == 0) {
        $url = substr($url, 7);
    } else if (strpos($url, 'https://') == 0) {
        $url = substr($url, 8);
    }

    $parts = explode('/', $url);

    $u .= $parts[count($parts)-1];

    $t = filemtime($u);

    if (!$t || time() - $t > CACHE_TTL) {

        if (strpos($url, 'www.') == 0) {
            $url = 'http://' . $url;
        }

        $data = file_get_contents($url);
        file_put_contents($u, $data);

    }

    return $root ? (ROOT_URL . '/' . $u) : $u;
}
