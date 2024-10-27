<?php

// Authenticate user
require_once 'common-helpers.php';
authenticate();

try {
    if (!empty($_POST['uri'])) {
        $uri = trim($_POST['uri']);
        if (strlen($uri) > 0) {
            require_once 'data-access.php';
            $db = connect_db();
            add_bm($db, $uri);
            $db->close();
        } else {
            error_log('Request param empty!');
        }
    } else {
        error_log('Required params not set');
    }
} catch (Exception $e) {
    error_log('An error occured due to working with db:'.$e->getMessage());
}

header('Location: /index.php', true, 303);
exit;
