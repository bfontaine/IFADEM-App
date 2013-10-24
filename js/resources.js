(function(w) {
    var appCache = w.applicationCache
                    || w.webkitApplicationCache
                    || w.mozApplicationCache
                    || w.msApplicationCache,

        $content = $('#content');


    if (!appcache) {
        $content.html('<p>Le cache n\'est pas supporté par votre navigateur.</p>');
        return;
    }

})(window);
