$(function() {

    // a click on an image shows a pop-up with some infos about a content,
    // while a click on the rest of the content (un)select it.
    $(document.body).on( 'click', '.content *', function( e ) {

        var $content = $(e.target).parent(), $checkbox;

        if (e.target.nodeName == 'IMG') {
            $( '#content-details-' + $content.data( 'contentId' ) ).popup().popup( 'open' );
            return;
        }

        $content.toggleClass( 'selected' );

    });


});
