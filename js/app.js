$(function() {

    var global_count = 0,
        $body        = $( 'body' ),
        $b_confirm   = $( '#select-contents' ),
        $b_cancel    = $('#b_cancel'),

        $pseudo_input = $( '#pseudo_inp' ),

        data_selected_count_attr = 'country-selected-count',
        data_selected_count_sel  = '.selected-count .count';

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


    // trying to fill the 'pseudo' input
    if (window.localStorage) {
        var p = localStorage.getItem( 'pseudo' );

        if (p && !$pseudo_input.val()) { $pseudo_input.val(p); }

        $pseudo_input.on( 'change', function() {

            localStorage.setItem( 'pseudo', $pseudo_input.val() );

        });
    }

    // contents' selection
    $b_confirm.click(function() {

        // get the pseudo
        var username = '',
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
                // TODO show confirmation pop-up
            },
            error: function(e) {
                // TODO
                console.log('Error while confirming selection:', e);
            }
        });

    });
    

});
