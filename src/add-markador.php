<?php

require_once('data-access.php');

try {
    if (isset($_POST['uri'])) {
        $bm_uri = trim($_POST['uri']);
        if (mb_strlen($bm_uri) > 0) {
            $db = connect_db();
            add_bm($db, $bm_uri);
            $db->close();
        } else {
            error_log('Request param empty!');
        }
    } else {
        error_log('HTTP method not POST');
    }
} catch (Exception $e) {
    error_log('An error occured due to working with db:'.$e->getMessage());
}


header('Location: /index.php', true, 303);
exit;

?>
