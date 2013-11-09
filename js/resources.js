(function(w) {
    var appCache = w.applicationCache
                    || w.webkitApplicationCache
                    || w.mozApplicationCache
                    || w.msApplicationCache,

        $content = $('#content');

    if (!appCache) {
        $content.html('<p>Le cache n\'est pas supporté par votre navigateur.</p>');
        return;
    }

    var $progress = $('#progress-status');

    if ($('li', $('#resources-list')).length > 0) {
        $progress.text('');
    }

    // ensure support of console.log
    if (!w.console) { w.console = {}; }
    if (!console.log) { console.log = function() {}; }

    function setCacheStatus( m ) {
        $progress.text(m);
    }

    // shortcut for event listeners
    function s( e ) {
        var o = {
            on: function(s, f) { e.addEventListener(s, f, false); return o; }
        };
        return o;
    }

    if (appCache.status == appCache.idle) {
        setCacheStatus('');
    }

    s(appCache).on('updateready', function() {
        appCache.swapCache();
        console.log('Cache updated');
        w.location.reload();
    }).on('error', function( e ) {
        console.log('AppCache error:', e);
    }).on('checking', function( e ) {
        console.log('Checking cache...');
    }).on('obsolete', function() {
        console.log('Obsolete cache');
        appCache.update();
    }).on('progress', function( e ) {
        // from:
        // www.jefclaes.be/2012/04/visualizing-offline-application-cache.html
        var msg = "Chargement des ressources... "
        if (e.lengthComputable) {
            setCacheStatus(msg + Math.round(e.loaded / e.total * 100) + '%');
        } else {
            setCacheStatus(msg);
        }
    });


})(window);
