<?php

/** Templates helpers */

/**
 * Merge default templates values with the given
 * array, and return a new array.
 **/
function tpl_array($a) {
    return array_merge_recursive(array(

        // default template values
        'lang' => 'fr',
        'dir'  => 'ltr'

    ), $a);
}

// shortcut
function tpl_render($tp, $values) {
    global $tpl_engine;
    return $tpl_engine->render($tp, tpl_array($values));
}

