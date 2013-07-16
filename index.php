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

    $ressources = get_ressources();

    $countries = array();

    foreach($ressources as $r) {

        $country = $r['country'];

        if (!isset($countries[$country['id']])) {
            $country['contents'] = array();
            $country['selected_count'] = 0; // number of selected ressources
            $countries[$country['id']] = $country;
        }

        $size = 1024 * (int)$r['size'];

        // MP3s
        $mp3s = get_mp3s($r['id']);

        foreach ($mp3s as $mp3) { $size += (int)$mp3['size']; }

        $r['size'] = tpl_size($size);
        $r['mp3s'] = $mp3s;
        $r['mp3s_count'] = count($mp3s);

        if (isset($r['selected']) && $r['selected']) {
            $countries[$country['id']]['selected_count'] += 1;
        }

        $countries[$country['id']]['contents'] []= $r;
    }

    return tpl_render('main.html', array(
        'countries' => $countries
    ));

}

function json($data) {
    header('Content-Type: text/javascript; charset=utf-8');
    return json_encode($data);
}

// API calls
function api($call) {

    if ($call == 'username') {
        // user's pseudo

        if (isset($_POST['u'])) {
            $username = trim($_POST['u']);
            register_username($username);
        }

        return array(
            'username' => get_username()
        );
    }
    if ($call == 'usernames') {
        // all pseudos
        return array(
            'usernames' => array_keys(get_users_data()),
            'username'  => get_username()
        );
    }
    if ($call == 'select-ressources') {
        if (!isset($_POST['username']) || !isset($_POST['ids'])) {
            return array( 'status' => 'error', 'message' => 'missing parameters.' );
        }

        $username = trim($_POST['username']);
        $ids      = explode(',', trim($_POST['ids']));

        if (empty($username) || empty($ids)) { return array( 'status' => 'error' ); }

        return array(
            'status' => update_podcasts($username, $ids) ? 'ok' : 'error',
            'url'    => podcasts_feed_url($username)
         );
    }

    return array();
}

// -- basic routing
if (!isset($_GET['p'])) {
    echo main_page();
} else {
    echo json(api($_GET['p']));
}
