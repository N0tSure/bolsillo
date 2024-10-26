<?php

$id = null;
$uri = null;
if (empty($_POST['id'] || empty($_POST['uri']))):
    error_log("Required form parameters not passed: id or/and uri");
else:
    require_once('common-helpers.php');

    $id = trim($_POST['id']);
    $uri = trim($_POST['uri']);
    if (!is_valid_id($id) || strlen($uri) == 0):
        error_log("Form params not valid: id = [".$id.'"], or/and uri = ["'.$uri.'"]');
    else:
        require_once('data-access.php');
        try {
            $db = connect_db();
            update_bm($db, $id, $uri);
            $db->close();
        } catch (Exception $e) {
            error_log('An error occurred: '.$e->getMessage());
        }
    endif;
endif;

header('Location: /index.php', true, 303);
exit;
