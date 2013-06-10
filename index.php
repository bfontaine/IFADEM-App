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

    $countries[$country['id']]['contents'] []= $r;
}

echo tpl_render('main.html', array(
    'countries' => $countries
));
