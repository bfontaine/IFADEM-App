<?php

/** Web services helpers */

function ws_call($fn, $args=array()) {

    $params = '?wsformat=json&wsfunction=' . urlencode($fn);

    foreach ($args as $k => $v) {
        $params .= '&' . urlencode($k) . '=' . urlencode($v);
    }

    $json = file_get_contents(WS_URL . $params);

    return json_decode($json, true);
}

/**
 * Return a standard lang code from the one returned by a call to the
 * Web services. For example, the standard code for 'fre' (French) is 'fr'.
 **/
function get_lang_code($c) {
    if ($c === 'fre') { return 'fr'; }
    return $c;
}

/**
 * Return an array of all countries:
 *  array(
 *      array( 'name' => 'Foo', 'id' => 1 ),
 *      array( 'name' => 'Bar', 'id' => 2 ),
 *      etc
 *  )
 **/
function get_countries() {
    $raw_countries = ws_call('getAllPays');

    $countries = array();

    foreach ($raw_countries as $_ => $country) {
        $countries []= array(
            'id'   => $country['Reference'],
            'name' => $country['Couverture']
        );
    }

    return $countries;
}

/**
 * Return an array of all ressources. Each ressource is an associative array
 * with the following keys: age, comment, description, difficulty, duration, format,
 * id, lang, licence, price, remarks, size, title, validated, version, content,
 * thumbnail, country, creation_date, modification_date.
 * The type of each value is not checked.
 **/
function get_ressources() {
    $raw_ressources = ws_call('getAllRessources');

    $ressources = array();

    foreach ($raw_ressources as $_ => $ressource) {
        $ressources []= array(

            'age'         => $ressource['Age'],
            'comment'     => $ressource['Commentaire'],
            'description' => $ressource['Description'],
            'difficulty'  => $ressource['Difficulte'],
            'duration'    => $ressource['Duree_Execution_Ressource'],
            'format'      => $ressource['Format'],
            'id'          => $ressource['Reference'],
            'lang'        => get_lang_code($ressource['Langue']),
            'licence'     => $ressource['Licence'],
            'price'       => $ressource['Cout'],
            'remarks'     => $ressource['Remarques_installation'],
            'size'        => $ressource['Taille_Ressource'],
            'title'       => $ressource['Titre'],
            'validated'   => $ressource['validation'],
            'version'     => $ressource['Version'],

            'content'     => $ressource['Entree_Identifiant'],
            'thumbnail'   => $ressource['Image'],

            'country'     => array(
                'id'   => $ressource['Couverture'],
                'name' => $ressource['liblPays']
            ),

            'creation_date'     => $ressource['Date_Creation'],
            'modification_date' => $ressource['Date_Modification']

        );
    }

    return $ressources;
}

/**
 * Return an array of all MP3s if called without any argument,
 * and return an array of MP3s for the ressources whose id is passed
 * as an argument, if any. Each MP3 is an associative array with
 * the following keys: id, ressource_id, url, size.
 **/
function get_mp3s($id=null) {
    $params = array();
    if ($id != null) {
        $params['critere'] = $id;
    }

    $raw_mp3s = ws_call('getAllMP3', $params);
    $mp3s = array();

    foreach ($mp3s as $mp3) {
        $mp3s []= array(
            'id'           => $mp3['id'],
            'ressource_id' => $mp3['Reference'],
            'url'          => $mp3['Entree_Identifiant']
        );
    }

    return $mp3;
}
