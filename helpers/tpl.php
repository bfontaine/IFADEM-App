<?php
/**
 * Templates helpers
 **/

/**
 * Merge default templates values with the given
 * array, and return a new array.
 **/
function tpl_array($a) {
    return array_merge_recursive(array(

        // default template values
        'lang' => 'fr',
        'dir'  => 'ltr',
        'app_js' => ENV == 'dev' ? 'js/app.js' : 'js/app.min.js'

    ), $a);
}

// shortcut
function tpl_render($tp, $values) {
    global $tpl_engine;
    return $tpl_engine->render($tp, tpl_array($values));
}

// return a human-readable size
function tpl_size($s) {

    $units = array('o', 'ko', 'Mo', 'Go');
    $unit_index = 0;

    while ($s > 1000) {
        // we keep one decimal
        $s = (int)($s/100);
        $s /= 10;

        $unit_index += 1;
    }

    return ''.$s . ' ' . $units[$unit_index];
}
