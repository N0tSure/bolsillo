<?php

require_once('print-helpers.php');
require_once('data-access.php');

function is_id_valid($id) {
    return preg_match('/^\d+$/', $id) === 1;
}

if (!empty($_GET['id'])) {
    $id = trim($_GET['id']);
    if (is_id_valid($id)) {
        try {
            $db = connect_db();
            $bm = get_bm($db, $id);

            print_header('Update Markador');

            echo '<h1>'.htmlspecialchars('Markador').'</h1>';
            echo '<form action="/update-markador.php" method="POST">';
            echo '<div>';
            echo '<label for="uri">Markador value: </label>';
            echo '<input type="text" name="uri" id="uri" value="'.$bm['uri'].'" required />';
            echo '<input type="hidden" name="id" value="'.$bm['id'].'" />';
            echo '</div>';
            echo '<div>';
            echo '<input type="submit" value="Update" />';
            echo '</div>';
            echo '</form>';

            $db->close();

            print_footer();

            exit;
        } catch (Exception $e) {
            error_log('An error occured: '.$e->getMessage());
        }
    } else {
        error_log('Bookmark id is not valid');
    }

} elseif (!empty($_POST['id']) && !empty($_POST['uri'])) {
    $id = trim($_POST['id']);
    $uri = trim($_POST['uri']);
    if (is_id_valid($id) && strlen($uri) > 0) {
        try {
            $db = connect_db();
            update_bm($db, $id, $uri);
            $db->close();
        } catch (Exception $e) {
            error_log('An error occured: '.$e->getMessage());
        }
    } else {
        error_log('Update parameter is not valid');
    }
} else {
    error_log('Bookmark id not set.');
}

header('Location: /index.php', true, 303);
exit;

?>
