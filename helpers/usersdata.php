<?php
/**
 * Users' data helpers
 *
 * Data is stored in a JSON file. Each user has an unique entry identified
 * by their id, e.g.:
 *      {
 *        "foo": {
 *          "resources": {
 *             "1": true,
 *             "5": true,
 *            "42": true
 *          }
 *        },
 *        "bar": {
 *          "resources": {}
 *        }
 *      }
 **/

// cache for users' data
$data_cache = null;

class User {
    private $userid;
    private $resources;
    private $loaded = false;

    public function __construct($id=null) {
        error_log("new [".$id."].");
        $this->id($id);
    }

    private function loadResources() {
        if ($this->id() == null) {
            return $this;
        }
        if ($this->loaded) { return; }
        $this->loaded = true;
        
        $data = load_user_data($this->id());
        $this->resources = $data['resources'];
    }

    /**
     * Set/get user's id
     **/
    public function id($id=null) {
        if ($id) { $this->userid = "$id"; return $this; }
        return $this->userid;
    }

    /**
     * Set/get users's selected resources
     **/
    public function resources($res=null) {
        if ($res) {
            $this->resources = $res;
            $this->loaded = true;
            return $this;
        }

        if (!$this->loaded) { $this->loadResources(); }
        return $this->resources;
    }

    // get RSS feed URL
    public function rss($root=true) {
        return podcasts_feed_url($this->id(), $root);
    }

    /**
     * Save user's data
     **/
    public function save() {
        save_user_data(array(
            'id'        => $this->id(),
            'resources' => $this->resources(),
            'rss'       => $this->rss()
        ));
        return $this;
    }

    public function toArray() {
        return array(
            'id' => $this->id(),
            'resources' => $this->resources()
        );
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
 * - resources: an array of ids of the resources this user has
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

// generate a random username
function random_username() {
    return 'user' . mt_rand(100, 99999);
}

function user() {
    $u = null;

    if (isset($_SESSION['user']) && ($_SESSION['user'] instanceof User)) {
        $u = $_SESSION['user'];
    }

    else if (isset($_COOKIE) && isset($_COOKIE['username'])) {
        $u = new User($_COOKIE['username']);
    }

    else { $u = new User(random_username()); }

    $_SESSION['user'] = $u;
    setcookie('username', $u->id(), time() + 2592000); // 1 month

    return $u;
}

