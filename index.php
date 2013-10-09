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
        }

        $countries[$country['id']]['contents'] []= $r;
    }

    return tpl_render('main.html', array(
        'user' => array(
            'id'      => $id,
            'rss_url' => $id ? podcasts_feed_url($id) : '',
            'has_selected_resources' => count($selected) > 0
        ),
        'user_json' => json_encode($user->toArray()),
        'countries' => $countries
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
    // -> current user
    if ($call == 'register-username') {
        if (!isset($_POST['username'])) {
            return $badparams;
        }
        $name = trim($_POST['username']);
        user()->id($name);
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

        update_podcasts(user()->id(), $ids);
        return user();
    }

    return $badparams;
}

// -- basic routing
if (!isset($_GET['api'])) {
    echo main_page();
} else {
    echo json(api($_GET['api']));
}
