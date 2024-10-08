<?php

require_once('data-access.php');

function delete_bm($db, $id) {
    $st = $db->prepare('DELETE FROM bookmark WHERE id = :id');
    if ($st) {
        $bm = addslashes($id);
        $br = $st->bindParam(':id', $bm, SQLITE3_INTEGER);
        if ($br) {
            if (!$st->execute()) {
                throw new Exception('Fail to execute prepared statment');
            }
        } else {
            throw new Exception('Unable to bind "id" prepared statment param');
        }
    } else {
        throw new Exception('Unable to prepare delete statement');
    }
}

try {
    if (isset($_POST['bm_id'])) {
        $id = trim($_POST['bm_id']);
        if (preg_match('/^\d+$/', $id) === 1) {
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
