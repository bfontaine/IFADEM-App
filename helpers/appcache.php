<?php

function manifest_url($username=null, $root=true) {
    if ($username === null) { $username = user()->id(); }
    return ($root ? ROOT_URL : '') . '/' . MANIFESTS_ROOT . "/$username.rss";
}

function update_manifest($username, $ids) {
    // FIXME the .manifest wants only local paths

    $subpath = manifest_url($username, false);
    $files = array();

    foreach ($ids as $_ => $id) {

        $content = get_resource($id);
        if (!$content) { continue; }

        $mp3s = get_mp3s($id);

        // content's PDF
        if ($content['content']) {
            $files []= $content['content'];
        }

        foreach ($mp3s as $mp3) {
            $files []= $mp3['url'];
        }
    }

    $str = implode("\n", $files);

    //return file_put_contents(ROOT_DIR . $subpath, $str) !== FALSE;
}
