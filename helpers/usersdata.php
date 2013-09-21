<?php
/**
 * Users' data helper
 **/

// cache for users' data
$data_cache = null;

class User {
    private $userid;
    private $resources;
    private $loaded = false;

    public function __construct($id) {
        $this->id($id);
    }

    private function loadResources() {
        if ($loaded) { return; }
        $loaded = true;
        
        $data = load_user_data($this->id());
        $this->resources = $data['resources'];
    }

    /**
     * Set/get user's id
     **/
    public function id($id=null) {
        if ($id) { $this->userid = "$id"; return $this; }
        return $this->userid();
    }

    /**
     * Set/get users's selected resources
     **/
    public function resources($res=null) {
        if ($res) {
            $this->resources = $res;
            $loaded = true;
            return $this;
        }

        if (!$this->loaded) { $this->loadResources(); }
        return $this->resources;
    }

    /**
     * Save user's data
     **/
    public function save() {
        save_user_data(array(
            'id'        => $this->id(),
            'resources' => $this->resources()
        ));
        return $this;
    }

    public function __toString() {
        return $this->id();
    }
}

/**
 * Load the user's data from the $usersdata files defined in the
 * settings file. This is an associative array with the following
 * keys:
 *
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
        'resources' => array()
    );

}

/**
 * Get all users' data
 **/
function get_users_data($force=false) {
    global $data_cache;
    if (!$force && $data_cache != null) { return $data_cache; }

    return $data_cache = json_decode(file_get_contents(DATA_FILE), true);
}

/**
 * Save user data. It must be an array, as returned by load_user_data.
 **/
function save_user_data($userdata) {
    $data = get_users_data();
    $data[$userdata['id']] = $userdata;
    file_put_contents($datafile, json_encode($data));

}

function get_user() {
    if (isset($_SESSION['user'])) {
        return $_SESSION['user'];
    }

    if (isset($_COOKIE) && isset($_COOKIE['username'])) {
        return $_SESSION['user'] = new User($_COOKIE['username']);
    }

    return null;
}

