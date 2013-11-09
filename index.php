<?php
/**
 * IFADEM Web app main file.
 *
 * This file is mostly an initialization file, all the logic will be
 * managed via JS, including navigation.
 *
 **/

require_once __DIR__ . '/initialization.php';

function main_page() {
    $user = user();
    $id   = $user->id() ? $user->id() : '';

    $selected   = $user->resources();
    $resources = get_resources();

    $countries = array();

    foreach($resources as $r) {

        $country = $r['country'];

        if (!isset($countries[$country['id']])) {
            $country['contents'] = array();
            $country['selected_count'] = 0; // number of selected resources
            $countries[$country['id']] = $country;
        }

        $size = 1024 * (int)$r['size'];

        // MP3s
        $mp3s = get_mp3s($r['id']);

        foreach ($mp3s as $mp3) { $size += (int)$mp3['size']; }

        $r['size'] = tpl_size($size);
        $r['mp3s'] = $mp3s;
        $r['mp3s_count'] = count($mp3s);

        $rid = ''.$r['id'];

        if (isset($selected[$rid]) && $selected[$rid]) {
            $countries[$country['id']]['selected_count'] += 1;
            $r['selected'] = true;
        }

        $countries[$country['id']]['contents'] []= $r;
    }

    $tpl_user = user()->toArray();

    if (count($selected) > 0) {
        $tpl_user['has_selected_resources'] = true;
    }

    return tpl_render('main.html', array(
        'user' => $tpl_user,
        'user_json' => json_encode($user->toArray()),
        'countries' => $countries
    ));

}

function resources_page() {
    $user = user();

    $res  = get_resources();
    $ures = $user->resources();

    $tpl_links = array();

    foreach ($res as $_ => $r) {
        if (!array_key_exists($r['id'], $ures) || !$ures[$r['id']]) {
            continue;
        }

        $tpl_link = array(
            'title' => $r['title'],
            'href'  => $r['content']
        );
        $tpl_mp3s = array();

        $mp3s = get_mp3s($r['id']);

        foreach ($mp3s as $_ => $m) {
            $tpl_mp3s[]= array(
                'title' => $m['title'],
                'href'  => $m['url']
            );
        }

        $tpl_link['other_links'] = $tpl_mp3s;
        $tpl_links []= $tpl_link;
    }
    
    return tpl_render('resources.html', array(
        'appcache_manifest' => manifest_url($user->id(), false),
        'resources' => $tpl_links
    ));
}

function json($data) {
    header('Content-Type: application/json; charset=utf-8');
    if ($data instanceof User) {
        $data = $data->toArray();
    }
    return json_encode($data);
}

// API calls
// /?api=<call>
function api($call) {
    $badparams = array( 'error' => 'bad parameters.' );

    // [GET] Get the current user
    // -> current user
    if ($call == 'user') {
        return user();
    }

    // [POST] Register an username
    // username: <name>
    // -> new current user
    if ($call == 'register-username') {
        if (!isset($_POST['username'])) {
            return $badparams;
        }
        $name = trim($_POST['username']);
        user(new User($name));
        return user();
    }

    // [POST] Select resources
    // ids: <comma separated resources ids>
    // -> current user
    if ($call == 'select-resources') {
        if (!isset($_POST['ids'])) {
            return $badparams;
        }

        $ids = explode(',', trim($_POST['ids']));

        $res = array();

        foreach ($ids as $id) {
            $res[$id] = true;
        }

        user()->resources($res)->save();
        return user();
    }

    // [*] Ping the server to check if the user is online
    // -> empty object
    if ($call == 'ping') {
        return array();
    }

    return $badparams;
}

// -- basic routing
if (!isset($_GET['api'])) {
    if (isset($_GET['p']) && $_GET['p'] == 'resources') {
        echo resources_page();
    } else {
        echo main_page();
    }
} else {
    echo json(api($_GET['api']));
}
