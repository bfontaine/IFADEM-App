var user = window.user;

$(function() {

    var api_calls = {
            user: 'GET ?api=user',
            username: 'POST ?api=register-username',
            resources: 'POST ?api=select-resources',
            ping: 'GET ?api=ping'
        },

        updating = false,
        isOnline,

        $body = $('body');

    if ( user ) {
        set_user( user );
    } else {
        update_user();
    }

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
     * @showLoad [Boolean]: if true, a loading spinner is
     * displayed (optional, default: true)
     *
     * Callback are given the following arguments:
     *  - data returned by the API
     *  - endpoint
     **/
    function api( endpoint, data, callback, err, opts ) {
        var url = api_calls[endpoint],
            mth;

        // set defaults
        err || (err = default_api_err);
        callback || (callback = $.noop);
        opts = $.extend(true, {}, opts);

        timeout = opts.timeout || 6000;
        
        if (!url) { err( {}, endpoint ); }

        url = url.split(' ', 2);
        mth = url[0];
        url = url[1];

        if (opts.showLoad) {
            $.mobile.loading('show');
        }

        updating = true;

        return $.ajax({
            url: url,
            method: mth,
            dataType: 'json',
            data: data,
            timeout: timeout,
            success: function( s ) {
                if (opts.showLoad) { $.mobile.loading('hide'); }
                updating = false;
                return callback( s, endpoint );
            },
            error: function( x, t, m ) {
                if (opts.showLoad) { $.mobile.loading('hide'); }
                updating = false;
                return err( x, t, m, endpoint );
            }
        });
    }

    function set_user( u ) {
        user = u;
        if ($.isArray( u.resources ) && u.resources.length == 0) {
            user.resources = {};
        }
    }

    // Update the current user
    function update_user() {
        api('user', {}, set_user);
        return {};
    }

    // Register the user's username
    function register_username() {
        api('username', {
            username: user.id
        }, set_user);
    }

    // Select resources
    function select_resources( cb ) {
        var res = user.resources, ids = [], id;

        cb || (cb = $.noop);

        for (id in res) { // not sure if Object.keys is supported
            if (res.hasOwnProperty(id)) {
                ids.push(id);
            }
        }

        api('resources', {
            ids: ids.join(',')
        }, function( u ) {
            set_user( u );
            cb();
        }, null, { showLoad: true, timeout: 30000 });
    }

    // ping the server and pass a boolean to a callback
    function ping( cb ) {
        cb || (cb = $.noop);

        api('ping', { t: 'Q'+Math.random() },
            function() { cb( true ); },
            function(_, t) {
            if (t === 'timeout') {
                cb(false);
            }
            console.log( 'Ping error: ' + t );
        });
    }

    /***********
     * Helpers *
     ***********/

    // return true if the user is online, false if not
    isOnline = (function() {
        var _online = true;
        
        if (window.navigator && window.navigator.onLine !== undefined) {
            return function() {
                return navigator.onLine;
            }
        }

        function _checkOnline() {
            ping(function( d ) { _online = d; });
            setTimeout(_checkOnline, 10000);
        }
        _checkOnline();

        return function() { return _online; };
    })();


    /******************
     * Pages Bindings *
     ******************/

    /** Landing Page **/
    (function __landing_page() {
        var $uform   = $( '#user-id-form' ),
            $uinput  = $( '#user-id' ),
            $sellink = $( '#link_selection' ),
            $reslink = $( '#link_resources' ),

            // update the username
            chg_username = function() {
                var new_id = $uinput.val().trim();

                if (!new_id || user.id == new_id) { return; }

                user.id = new_id;
                register_username();
            };

        $uform.submit(function( e ) {
            chg_username();
            e.preventDefault();
            return false;
        });

        $uinput.change(chg_username);

        $.each([ $reslink, $sellink ], function(i, $e) {

            $e.click(function( e ) {
                if (updating) {
                    e.preventDefault();
                    $.mobile.loading('show');
                    setTimeout(function() {
                        $e.click();
                    }, 500);
                    return false;
                }
            });

        });

    })();

    /** Selection Page **/
    (function __selection_page() {
        var $page    = $( '#selection-page' ),
            $cancel  = $('#b_cancel'),
            $confirm = $('#select-contents'),

            global_count = 0,

            data_selected = {
                count_attr: 'country-selected-count',
                count_sel: '.selected-count .count'
            };

        function update_selected_size() {
            // TODO
        }

        // show/hide the 'cancel' button which deselect all contents
        function update_cancel_button() {
            $cancel.toggleClass('nodisplay', global_count === 0);
        }

        // a click on an image shows a pop-up with some infos about a content,
        // while a click on the rest of the content (un)select it.
        $body.on( 'click', '#selection-page .content', function( e ) {

            var nodeName = e.target.nodeName.toUpperCase(),
                $content = nodeName == 'LI' ? $(e.target) : $(e.target).parent(),

                $checkbox, $count;

            // set the data-country-selected-count attribute
            if (!$content.data( data_selected.count_attr )) {

                $content.data( data_selected.count_attr,
                               $content
                                    .parent().parent().parent()
                                    .find( data_selected.count_sel ));

            }

            if (nodeName == 'IMG') {
                $( '#content-details-' + $content.data( 'contentId' ) )
                    .popup()
                    .popup( 'open' )
                    // close on click
                    .one( 'click', function() { $(this).popup( 'close' ); });
            } else {
                $content.toggleClass( 'selected' );
                $count = $content.data( data_selected.count_attr );

                // update the local count
                $count.text( $content.parents('div.ui-collapsible-content')
                                     .first()
                                     .find('.selected.content').length );

                // update the global count
                global_count = $('.selected.content').length;

                update_cancel_button();
            }

        });

        $( '#confirm-cancelling' ).on( 'click', function() {

            global_count = 0;
            update_cancel_button();

            $body.find( data_selected.count_sel ).map(function(i, e) {
                e.innerText = e.textContent = 0;
            });

            $body.find( '.content.selected' ).map(function(i, e) {
                $( e ).removeClass( 'selected' );
            });

            user.resources = [];
            select_resources();

        });

        $('a[data-role="back"]').click(function() {
            $('.ui-collapsible').trigger('collapse');
        });

        // contents' selection
        $confirm.click(function() {
            var ids = $( '.content.selected' ).map(function(i, e) {
                return $(e).data('contentId');
            }).toArray(), i;

            user.resources = {};
            for (i in ids) {
                if (ids.hasOwnProperty(i)) {
                    user.resources[ids[i]] = true;
                }
            }

            select_resources(function() {
                $('.ui-collapsible').trigger('collapse');
                $.mobile.navigate('#landingpage');
            });

        });

        $page.on('pagebeforeshow', function() {
            update_cancel_button();
            global_count = 0;

            /* update selected resources */
            if (user && user.resources) {
                $( '.content' ).each(function( _, c ) {
                    var $c = $(c),
                        id = $c.data('contentId');

                    if (user.resources[+id] || user.resources[id]) {
                        global_count++;
                        $c.addClass( 'selected' );
                    } else {
                        global_count--;
                        $c.removeClass( 'selected' );
                    }
                });

                // update sub-counts
                $('.ui-collapsible').each(function( i, e ) {
                    var $e = $(e),
                        $count = $e.find('span.count').first();

                    $count.text( $e.find('.selected.content').length );

                });

            }

            (global_count < 0) && (global_count = 0);
        });
        
    })();

    /** 'view resources' page **/
    (function __view_resources_page() {
        var $page = $('#view-resources-page'),
            $rss  = $('#rss-feed-url');
        
        $page.on('pagebeforeshow', function() {
            if ( user.rss_url ) {
                $rss.val(user.rss_url);
            }
        });

    })();

});
