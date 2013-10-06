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

        if ($selected[''.$r['id']]) {
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
        'countries' => $countries
    ));

}

function json($data) {
    header('Content-Type: application/json; charset=utf-8');
    return json_encode($data);
}

// API calls
// /?api=<call>
function api($call) {
    $badparams = array( 'error' => 'bad parameters.' );

    // [POST] Register an username
    // username: <name>
    // -> current user
    if ($call == 'register-username') {
        if (!isset($_POST['username'])) {
            return $badparams;
        }
        $name = trim($_POST['username']);
        return user()->id($name)->toArray();
    }

    // [POST] Select resources
    // ids: <comma separated resources ids>
    // -> current user
    if ($call == 'select-resources') {
        if (!isset($_POST['ids'])) {
            return $badparams;
        }

        $ids = explode(',', trim($_POST['ids']));

        $me = user();

        update_podcasts($me->id(), $ids);
        return $me->toArray();
    }

    return $badparams;
}

// -- basic routing
if (!isset($_GET['api'])) {
    echo main_page();
} else {
    echo json(api($_GET['api']));
}
