<?php
/**
 * Users' data helper
 **/

/**
 * Load the user's data from the $usersdata files defined in the
 * settings file. It's an associative array, with the following
 * keys:
 *
 * - userid: the user id (username)
 * - ressources: an array of ids of the ressources this user has
 *   selected. May be empty.
 **/
function load_user_data($userid=null) {

    if ($userid != null) {
        $data = get_users_data();

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
 * Get all users' data
 **/
function get_users_data() {
    return json_decode(file_get_contents(DATA_FILE), true);
}

/**
 * Save user data. It must be an array, as returned by load_user_data.
 **/
function save_user_data($userdata) {
    $data = get_users_data();
    $data[$userdata['id']] = $userdata;
    file_put_contents($datafile, json_encode($data));

}

/**
 * Register an username for the current user
 **/
function register_username($username) {
    // TODO
    return false;
}

function get_username() {
    if (!isset($_COOKIE) || !isset($_COOKIE['username'])) { return false; }

    return $_COOKIE['username'];
}
