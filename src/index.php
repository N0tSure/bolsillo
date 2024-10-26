<?php
// Functions
function halt($msg) {
    echo '<div class="error-message">';
    echo '<span class="error-message-text">'.htmlspecialchars($msg).'</span>';
    echo '</div>';
    exit;
}

// Print header
$title = 'Home';
include_once 'header.php';
include_once 'navbar.php';

// Header
echo '<div class="header">';
echo '<h1>Welcome to Bolsillo!</h1>';
echo '</div>';

// Main content start
echo '<div class="row">';
echo '<div class="column">';

require_once('data-access.php');
$db = null;
try {
    $db = connect_db();
} catch (Exception $e) {
    halt($e->getMessage());
}

$bms = null;
try {
    $bms = get_bm_list($db);
    $db->close();
} catch (Exception $e) {
    $db->close();
    halt($e->getMessage());
}

// Add Marcador form
echo '<div class="add-marcador main-buttons">';
echo '<form action="add-markador.php" method="post">';
echo '<label for="new_uri">Save a new Marcador to Bolsillo</label>';
echo '<input type="text" id="new_uri" name="uri" placeholder="Marcador URL"/>';
echo '<input type="submit" value="Add"/>';
echo '<input type="reset" value="Clear"/>';
echo '</form>';
echo '</div>';

// Bookmarks
echo '<div>';
echo '<p>There are Marcadores that you saved.</p>';
echo '</div>';
echo '<div class="marcador-container">';

foreach ($bms as $id => $bm) {
    // Marcador
    echo '<div class="marcador">';

    // Marcador Link (anchor element)
    echo '<a href="'.$bm.'">';
    echo '<span>'.htmlspecialchars($bm).'</span>';
    echo '</a>';

    // Delete Marcador form
    echo '<form class="main-buttons" action="delete-markador.php" method="POST">';
    echo '<input type="hidden" name="bm_id" value="'.$id.'"/>';
    echo '<input type="submit" value="Delete"/>';
    echo '</form>';

    // Update Marcador form
    echo '<form class="main-buttons" action="update-marcador-form.php" method="GET">';
    echo '<input type="hidden" name="id" value="'.$id.'"/>';
    echo '<input type="submit" value="Update"/>';
    echo '</form>';

    echo '</div>';
}

echo '</div>';

// Main content end
echo '</div>';
echo '</div>';

// Footer
print_footer();
