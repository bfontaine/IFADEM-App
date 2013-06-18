$(function() {

    // a click on an image shows a pop-up with some infos about a content,
    // while a click on the rest of the content (un)select it.
    $(document.body).on( 'click', '.content', function( e ) {

        var nodeName = e.target.nodeName.toUpperCase(),
            $content = nodeName == 'LI' ? $(e.target) : $(e.target).parent(),
            $checkbox;

        if (nodeName == 'IMG') {
            $( '#content-details-' + $content.data( 'contentId' ) )
                .popup()
                .popup( 'open' )
                // close on click
                .one( 'click', function() { $(this).popup( 'close' ); });
        } else {
            $content.toggleClass( 'selected' );
        }

    });


});
