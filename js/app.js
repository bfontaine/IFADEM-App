$(function() {

    var user = {}, // current user
    
        api_calls = {
            user: 'GET /?api=user',
            username: 'POST /?api=register-username',
            resources: 'POST /?select-resources'
        };

    /*****************
     * Compatibility *
     *****************/

    if (!(typeof String.prototype.trim == 'function')) {
        var trailing_spaces = /^\s+|\s+$/g;
        
        String.prototype.trim = function() {
            return this.replace(trailing_spaces, '');
        };
    }

    if (!window.console) { window.console = {log: $.noop}; }

    /*************
     * API Calls *
     *************/

    function default_api_err( d, ep ) {
        console.log("API Error [" + ep + "]: " + JSON.stringify(d));
    }

    /**
     * Perform a call to the server API.
     * @endpoint [String]: name of the endpoint
     * @data [Object]: params to give to the server
     * @callback [Function]: success callback (optional)
     * @err [Function]: error callback (optional)
     *
     * Callback are given the following arguments:
     *  - data returned by the API
     *  - endpoint
     **/
    function api( endpoint, data, callback, err ) {
        var url = api_calls[endpoint],
            mth;

        err || (err = default_api_err);
        callback || (callback = $.noop);
        
        if (!url) { err( {}, endpoint ); }

        url = url.split(' ', 2);
        mth = url[0];
        url = url[1];

        return $.ajax({
            url: url,
            method: mth,
            dataType: 'json',
            data: data,
            success: function( s ) {
                return callback( s, endpoint );
            },
            error: function( s ) {
                return err( s, endpoint );
            }
        });
    }

    // Update the current user
    function update_user() {
        api('user', {}, function( u ) {
            user = u;
        });
    }

    // Register the user's username
    function register_username() {
        api('username', {
            username: user.id
        }, function( u ) {
            user = u; 
        });
    }

    // Select resources
    function select_resources() {
        var res = user.resources, ids = [], id;

        for (id in res) { // not sure if Object.keys is supported
            if (res.hasOwnProperty(id)) {
                ids.push(id);
            }
        }

        api('select-resources', {
            ids: ids
        }, function( u ) {
            user = u;
        });
    }

    if (window.user) {
        user = window.user;
        delete window.user;
    } else {
        update_user();
    }

    /******************
     * Pages Bindings *
     ******************/

    /** Landing Page **/
    (function __landing_page() {
        var $uform  = $( '#user-id-form' ),
            $uinput = $( '#user-id' ),

            // update the username
            chg_username = function() {
                var new_id = $uinput.val().trim();

                if (user.id == new_id) { return; }

                user.id = new_id;
                register_username();
            };

        $uform.submit(function( e ) {
            chg_username();
            e.preventDefault();
            return false;
        });

        $uinput.change(chg_username);

    })();

    /*
    
    // TODO

    // ========= OLD CODE ======================== //

    var global_count = 0,
        $body        = $( 'body' ),
        $b_confirm   = $( '#select-contents' ),
        $b_cancel    = $('#b_cancel'),

        $username_input = $( '#pseudo_inp' ),

        data_selected_count_attr = 'country-selected-count',
        data_selected_count_sel  = '.selected-count .count',


    // show/hide the 'cancel' button which deselect all contents
    function update_cancel_button() {
        $b_cancel.toggleClass('nodisplay', global_count === 0);
    }

    // a click on an image shows a pop-up with some infos about a content,
    // while a click on the rest of the content (un)select it.
    $body.on( 'click', '.content', function( e ) {

        var nodeName = e.target.nodeName.toUpperCase(),
            $content = nodeName == 'LI' ? $(e.target) : $(e.target).parent(),

            $checkbox, $count, diff;

        // set the data-country-selected-count attribute
        if (!$content.data( data_selected_count_attr )) {

            $content.data( data_selected_count_attr,
                           $content
                                .parent().parent().parent()
                                .find( data_selected_count_sel ));

        }

        if (nodeName == 'IMG') {
            $( '#content-details-' + $content.data( 'contentId' ) )
                .popup()
                .popup( 'open' )
                // close on click
                .one( 'click', function() { $(this).popup( 'close' ); });
        } else {
            $content.toggleClass( 'selected' );
            $count = $content.data( data_selected_count_attr );

            diff = $content.hasClass( 'selected' ) ? 1 : -1;

            // update the local count
            $count.text( +$count.text() + diff);

            // update the global count
            global_count += diff;

            update_cancel_button();
        }

    });

    $( '#confirm-cancelling' ).on( 'click', function() {

        global_count = 0;
        update_cancel_button();

        $body.find( data_selected_count_sel ).map(function(i, e) {
            e.innerText = e.textContent = 0;
        });

        $body.find( '.content.selected' ).map(function(i, e) {
            $( e ).removeClass( 'selected' );
        });

    });

    // contents' selection
    $b_confirm.click(function() {

        // get the username
        var username = $username_input.val().trim(),
        // get the contents' ids
            ids = $( '.content.selected' ).map(function(i, e) {
                    return $(e).data('contentId'); }).toArray();

        $.ajax({
            method: 'POST',
            url: '/?p=select-resources',
            data: {
                username: username,
                ids: ids.join(',')
            },
            success: function(data) {

                // tmp code, for testing purposes

                if (data.status !== 'ok') {
                    console.log(data);
                    alert('error!');
                    return;
                }

                prompt('ok', data.url);
            },
            error: function() {
                console.log("selection AJAX error:");
                console.log([].slice.call(arguments));
            }
        });

    });
    
    */
});
