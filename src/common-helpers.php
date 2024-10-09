<?php

/*
 * Checks that provided id is number.
 *
 * :param id string for validation
 * :retrun true if id is number
 */
function is_valid_id($id) {
    return preg_match('/^\d+$/', $id) === 1;
}

?>
