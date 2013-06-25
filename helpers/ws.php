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
            'lang_code'   => get_lang_code($ressource['Langue']),
            'lang'        => get_lang_name($ressource['Langue']),
            'licence'     => $ressource['Licence'],
            'price'       => $ressource['Cout'],
            'remarks'     => $ressource['Remarques_installation'],
            'size'        => (int)$ressource['Taille_Ressource'],
            'title'       => $ressource['Titre'],
            'validated'   => $ressource['validation'],
            'version'     => $ressource['Version'],

            'content'     => $ressource['Entree_Identifiant'],
            'thumbnail'   => $ressource['Image']
                                    ? $ressource['Image']
                                    : 'imgs/default-icon.png',

            'country'     => array(
                'id'   => $ressource['Couverture'],
                'name' => $ressource['liblPays']
            ),

            'creation_date'     => $ressource['Date_Creation'],
            'modification_date' =>
                $ressource['Date_Modification'] !== $ressource['Date_Creation']
                    ? $ressource['Date_Modification']
                    : null

        );
    }

    return $ressources;
}

/**
 * Return an array of all MP3s if called without any argument,
 * and return an array of MP3s for the ressource whose id is passed
 * as an argument, if any. Each MP3 is an associative array with
 * the following keys: id, ressource_id, url, size.
 **/
function get_mp3s($id=null) {
    $params = array();
    if ($id != null) {
        $params['critere'] = 'Reference=' . $id;
    }

    $raw_mp3s = ws_call('getAllMP3', $params);
    $mp3s = array();

    foreach ($raw_mp3s as $mp3) {
        $url = $mp3['Entree_Identifiant'];

        // skip ZIP files
        if (strripos($url, '.zip', -4) === -4) { continue; }

        $mp3s []= array(
            'id'           => $mp3['id'],
            'ressource_id' => $mp3['Reference'],
            'url'          => $url,
            'size'         => (int)$mp3['Taille']
        );
    }

    return $mp3s;
}

/**
 * Return an array of all tags if called without any argument,
 * and return an array of tags for the ressource whose id is passed
 * as an argument, if any. Each tag is an associative array with
 * the following keys: id, ressource_id, name.
 **/
function get_tags($id=null) {
    $params = array();
    if ($id != null) {
        $params['critere'] = 'Reference=' . $id;
    }

    $raw_tags = ws_call('getAllTags', $params);
    $tags = array();

    foreach ($raw_tags as $tag) {
        $tags []= array(
            'id'           => $tag['id'],
            'ressource_id' => $tag['Reference'],
            'name'         => $tag['Mot_clef_libre']
        );
    }

    return $tags;

}
