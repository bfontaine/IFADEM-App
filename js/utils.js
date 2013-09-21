function utils() {

    var user = window.user || {},
        p;

    // load username from localStorage if it's available
    if ( !user.id && window.localStorage ) {
        p = localStorage.getItem('username');

        if ( p ) {
            user.id = p;
        }
    }

    // save username to localStorage if it's available
    user.listenForName = !window.localStorage ? $.noop : function($e) {
        $e.on( 'change', function() {
            localStorage.setItem('username', user.id = $e.val());
        });

        if (user.id && $e.val().length == 0) {
            $e.val(user.id);
        }
    };

    // cleaning global namespace
    delete window.user;

    return {
        user : user
    };

};
