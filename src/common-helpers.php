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

/**
 * This function authenticates user. Must be called at the start of script.
 * Forces user to login.
 * @return void
 */
function authenticate()
{
    session_start();
    if (empty($_SESSION['user_id'])):
        header('Location: login.php', true, 303);
        exit;
    endif;
}
