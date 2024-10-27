<?php

// Authenticate user
require_once 'common-helpers.php';
authenticate();

// Go home whether no Marcador id passed
if (empty($_GET['id'])):
    error_log('No Marcador id request param passed');
    header('Location: /index.php', true, 303);
    exit(1);
endif;

$id = $_GET['id'];

// Go home whether Marcador id isn't valid
if (!is_valid_id($id)):
    error_log('Invalid request parameter, id = "'.$id.'"');
    header('Location: /index.php', true, 303);
    exit(1);
endif;

// Print page header
$title = 'Update Marcador';
include_once 'header.php';
include_once 'navbar.php';

// Getting Marcador for update
require_once('data-access.php');
$bm = null;
try {
    $db = connect_db();
    $bm = get_bm($db, $id);
    $db->close();
} catch (Exception $exception) {

    // Show error and exit
    echo '<div class="error-message">';
    echo '<span class="error-message-text">'.htmlspecialchars($exception->getMessage()).'</span>';
    echo '</div>';
    echo '</body>';
    echo '</html>';
    exit;
}

?>

<div class="header">
    <h1>Update Marcador</h1>
</div>
<div class="row">
    <div class="column">
        <div class="add-marcador main-buttons">
            <form action="update-marcador.php" method="POST">
                <label for="uri">Update your Marcador in Bolsillo</label>
                <input type="text" name="uri" id="uri" value="<?= $bm['uri'] ?>" required />
                <input type="hidden" name="id" value="<?= $bm['id'] ?>"/>
                <input type="submit" value="Update"/>
            </form>
        </div>
    </div>
</div>

<?php

// Print footer
print_footer();
?>
