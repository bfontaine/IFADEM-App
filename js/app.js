$(function() {

    var global_count = 0,
        $body        = $( 'body' ),
        $b_confirm   = $( '#select-contents' ),
        $b_cancel    = $('#b_cancel'),

        $username_input = $( '#pseudo_inp' ),

        data_selected_count_attr = 'country-selected-count',
        data_selected_count_sel  = '.selected-count .count',

        // not sure if String#trim is supported by all browsers
        trim = (function() {
            var trailing_spaces = /^\s+|\s+$/g;
            return function(s) { return s.replace(trailing_spaces, ''); };
        })();


    if (!window.console) { window.console = {log: $.noop}; }

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


    // trying to fill the 'username' input
    if (window.localStorage) {
        var p = localStorage.getItem( 'username' );

        if (p && !$username_input.val()) { $username_input.val(p); }

        $username_input.on( 'change', function() {

            localStorage.setItem( 'username', $username_input.val() );

        });
    }

    // contents' selection
    $b_confirm.click(function() {

        // get the username
        var username = trim($username_input.val()),
        // get the contents' ids
            ids = $( '.content.selected' ).map(function(i, e) {
                    return $(e).data('contentId'); }).toArray();

        $.ajax({
            method: 'POST',
            url: '/?p=select-ressources',
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
            error: function(e) {
                // TODO
                console.log('Error while confirming selection:', e);
            }
        });

    });
    

});
