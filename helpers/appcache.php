<?php

function manifest_url($username=null, $root=true) {
    if ($username === null) { $username = user()->id(); }
    return ($root ? ROOT_URL . '/' : '') . MANIFESTS_ROOT . "/$username.appcache";
}

function update_manifest($username, $ids) {

    $subpath = manifest_url($username, false);
    $files = array();
    $hash = '';

    /*
        Note that we have to prepend '../' to files paths because the manifest is
        in p/, while the paths start with resources/ so we need to replace this
        with ../resources/ to get the correct relative path.
     */

    foreach ($ids as $_ => $id) {

        $content = get_resource($id, true, false);
        if (!$content) { continue; }

        $mp3s = get_mp3s($id, true, false);

        // content's PDF
        if ($content['content']) {
            $files []= '../' . $content['content'];
        }

        foreach ($mp3s as $mp3) {
            if ($mp3['url']) {
                $files []= '../' . $mp3['url'];
            }
        }

        $hash .= "-$id";
    }

    $hash = md5($hash);
    $list = implode("\n", $files);

    $content = "CACHE MANIFEST\n# v$hash\n$list\n";

    return file_put_contents(ROOT_DIR . '/' . $subpath, $content) !== FALSE;
}
