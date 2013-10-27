<?php
/**
 * Web services helpers
 **/

/**
 * Web services call
 **/
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
 * Return an array of all resources. Each resource is an associative array
 * with the following keys: age, comment, description, difficulty, duration, format,
 * id, lang, licence, price, remarks, size, title, validated, version, content,
 * thumbnail, country, creation_date, modification_date.
 * The type of each value is not checked.
 **/
function get_resources($criteria=null, $cache=false) {
    $cache = true; // test

    $args = array();
    $resources = array();

    if ($criteria) {
        $args['critere'] = $criteria;
    }

    $raw_resources = ws_call('getAllRessources', $args);

    if (!$raw_resources) { return $resources; }

    foreach ($raw_resources as $_ => $resource) {
        $url = $resource['Entree_Identifiant'];

        if (!$url) { continue; }

        $r = array(

            'age'         => $resource['Age'],
            'comment'     => $resource['Commentaire'],
            'description' => $resource['Description'],
            'difficulty'  => $resource['Difficulte'],
            'duration'    => $resource['Duree_Execution_Ressource'],
            'format'      => $resource['Format'],
            'id'          => $resource['Reference'],
            'lang_code'   => get_lang_code($resource['Langue']),
            'lang'        => get_lang_name($resource['Langue']),
            'licence'     => $resource['Licence'],
            'price'       => $resource['Cout'],
            'remarks'     => $resource['Remarques_installation'],
            'size'        => (int)$resource['Taille_Ressource'],
            'title'       => $resource['Titre'],
            'validated'   => $resource['validation'],
            'version'     => $resource['Version'],

            'content'     => $cache ? cache_res($url) : $url,
            'thumbnail'   => $resource['Image']
                                    ? $resource['Image']
                                    : 'imgs/default-icon.png',

            'country'     => array(
                'id'   => $resource['Couverture'],
                'name' => $resource['liblPays']
            ),

            'creation_date'     => $resource['Date_Creation'],
            'modification_date' =>
                $resource['Date_Modification'] !== $resource['Date_Creation']
                    ? $resource['Date_Modification']
                    : null

        );

        $resources []= $r;
    }

    return $resources;
}

/**
 * Find a resource by its id. See [get_resources] for the return
 * value.
 **/
function get_resource($id, $cache=false) {
    $res = get_resources("Reference=$id", $cache);

    if ($res) { return $res[0]; }

    return null;
}

/**
 * Return an array of all MP3s if called without any argument,
 * and return an array of MP3s for the resource whose id is passed
 * as an argument, if any. Each MP3 is an associative array with
 * the following keys: id, resource_id, url, size.
 **/
function get_mp3s($id=null, $cache=false) {
    $params = array();
    if ($id != null) {
        $params['critere'] = 'Reference=' . $id;
    }

    $raw_mp3s = ws_call('getAllMP3', $params);
    $mp3s = array();

    foreach ($raw_mp3s as $mp3) {
        $url = $mp3['Entree_Identifiant'];
        $len = strlen($url);

        if ($len == 0) { continue; }

        // skip ZIP files
        if (strripos($url, '.zip', $len - 4) !== FALSE) { continue; }

        $mp3s []= array(
            'id'          => $mp3['id'],
            'resource_id' => $mp3['Reference'],
            'url'         => $cache ? cache_res($url) : $url,
            'size'        => (double)$mp3['Taille'],
            'title'       => $mp3['titre_mp3_ws']
        );
    }

    return $mp3s;
}

/**
 * Return an array of all tags if called without any argument,
 * and return an array of tags for the resource whose id is passed
 * as an argument, if any. Each tag is an associative array with
 * the following keys: id, resource_id, name.
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
            'id'          => $tag['id'],
            'resource_id' => $tag['Reference'],
            'name'        => $tag['Mot_clef_libre']
        );
    }

    return $tags;

}
