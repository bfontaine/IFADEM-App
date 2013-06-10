<?php
/**
 * Users' data helper
 **/

/**
 * Load the user's data from the $usersdata files defined in the
 * settings file. It's an associative array, with the following
 * keys:
 *
 * - userid: the user id
 * - ressources: an array of ids of the ressources this user has
 *   selected. May be empty.
 **/
function load_user_data($userid=null) {

    if ($userid != null) {
        $data = json_decode(file_get_contents($datafile));

        if (isset($data[$userid])) {
            return $data[$userid];
        }
    }

    return array(
        'id'         => $userid,
        'ressources' => array()
    );

}

/**
 * Save user data. It must be an array, as returned by load_user_data.
 **/
function save_user_data($userdata) {

    $data = json_decode(file_get_contents($datafile));
    $data[$userdata['id']] = $userdata;
    file_put_contents($datafile, json_encode($data));

}
