<?php

require_once('print-helpers.php');
require_once('data-access.php');

function get_bm($db, $id) {
    $result = null;
    $st = $db->prepare('SELECT * FROM bookmark WHERE id = :id');
    if ($st) {
        $b = $st->bindParam(':id', $id, SQLITE3_INTEGER);
        if ($b) {
            $rs = $st->execute();
            if ($rs) {
                $result = $rs->fetchArray(SQLITE3_ASSOC);
                if (!$result) {
                    throw new Exception('No bookmark with given id = "'.$id.'"');
                }
            } else {
                throw new Exception('Fail to execute prepared statement');
            }
        } else {
            throw new Exception('Unable to bind id param to prepared statement');
        }

        $st->close();
    } else {
        throw new Exception('Unable to prepare statement');
    }

    return $result;
}

function is_id_valid($id) {
    return preg_match('/^\d+$/', $id) === 1;
}

function update_bm($db, $id, $uri) {
    $st = $db->prepare('UPDATE bookmark SET uri = :uri WHERE id = :id');
    if ($st) {
        $b = $st->bindValue(':id', $id, SQLITE3_INTEGER);
        if ($b) {
            $b = $st->bindValue(':uri', $uri, SQLITE3_TEXT);
            if ($b) {
                if(!$st->execute()) {
                    throw new Exception('Fail to execute prepared statement');
                }
            } else {
                throw new Exception('Unable to bind uri param to prepared statement');
            }
        } else {
            throw new Exception('Unable to bind id param to prepared statement');
        }

        $st->close();
    } else {
        throw new Exception('Unable to prepare statement');
    }
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
