<?php

require_once('data-access.php');

if (isset($_POST['uri'])) {
    $bm_uri = addslashes(trim($_POST['uri']));
    if (mb_strlen($bm_uri) > 0) {
        try {
            $db = connect_db();
            add_bm($db, $bm_uri);
        } catch (Exception $e) {
            error_log('An error occured due to working with db:'.$e->getMessage());
        }
    } else {
        error_log('Request param empty!');
    }
} else {
    error_log('HTTP method not POST');
}


header('Location: /index.php', true, 303);
exit;

?>
