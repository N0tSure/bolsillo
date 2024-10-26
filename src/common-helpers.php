<?php

/*
 * Checks that provided id is number.
 *
 * :param id string for validation
 * :return true if id is number
 */
function is_valid_id($id) {
    return preg_match('/^\d+$/', $id) === 1;
}

/*
 * Prints HTML page footer
 */
function print_footer() {
    echo '</body>';
    echo '</html>';
}
