<?php

require_once('data-access.php');
require_once('common-helpers.php');

try {
    if (!empty($_POST['bm_id'])) {
        $id = trim($_POST['bm_id']);
        if (is_valid_id($id)) {
            $db = connect_db();
            delete_bm($db, $id);
            $db->close();
        } else {
            error_log('Parameter invalid');
        }
    } else {
        error_log('Request param not set');
    }
} catch (Exception $e) {
    error_log('An error occured: '.$e->getMessage());
}

header('Location: /index.php', true, 303);
exit;

?>
