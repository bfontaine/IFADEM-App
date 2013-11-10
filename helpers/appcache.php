<?php

function manifest_url($username=null, $root=true) {
    if ($username === null) { $username = user()->id(); }
    return ($root ? ROOT_URL . '/' : '') . MANIFESTS_ROOT . "/$username.appcache";
}

function update_manifest($username, $ids) {

    $subpath = manifest_url($username, false);
    $files = array(
        # Default files, included in all .appcache manifests
        '../css/main.css',

        '../js/jquery.mobile-1.3.1.min.css',
        '../js/jquery-1.9.1.min.js',
        '../js/jquery.mobile-1.3.1.min.js',
        '../js/images/icons-18-white.png',
        '../js/images/ajax-loader.gif',

        '../js/resources.js',

        # jQuery Mobile icons
        '../js/images/icons-18-white.png',

        '../imgs/ifadem-logo.png',
        '../imgs/oif.png',
        '../imgs/auf.png'
    );
    $hash = '';

    foreach ($ids as $_ => $id) {

        $content = get_resource($id, true, false);
        if (!$content) { continue; }

        $mp3s = get_mp3s($id, true, false);

        // content's PDF
        if ($content['content']) {
            $files []= $content['content'];
        }

        foreach ($mp3s as $mp3) {
            if ($mp3['url']) {
                $files []= $mp3['url'];
            }
        }

        $hash .= "-$id";
    }

    $hash = md5($hash);
    $list = implode("\n", $files);

    $dir = current_url_dir();

    $content  = "CACHE MANIFEST\n# v$hash\n$list\n\n";
    $content .= "FALLBACK:\n$dir $dir/index.php?p=offline\n";

    return file_put_contents(ROOT_DIR . '/' . $subpath, $content) !== FALSE;
}
