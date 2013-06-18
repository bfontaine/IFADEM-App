<?php
/**
 * IFADEM Web app main file.
 *
 * This file is mostly an initialization file, all the logic will be
 * managed via JS, including navigation.
 *
 **/

require_once __DIR__ . '/initialization.php';

$ressources = get_ressources();

$countries = array();

foreach($ressources as $r) {

    $country = $r['country'];

    if (!isset($countries[$country['id']])) {
        $country['contents'] = array();
        $countries[$country['id']] = $country;
    }

    $size = (int)$r['size'];

    // MP3s
    $mp3s = get_mp3s($r['id']);

    foreach ($mp3s as $mp3) { $size += (int)$mp3['size']; }

    $r['size'] = tpl_size($size);
    $r['mp3s'] = $mp3s;
    $r['mp3s_count'] = count($mp3s);

    $countries[$country['id']]['contents'] []= $r;
}

echo tpl_render('main.html', array(
    'countries' => $countries
));
