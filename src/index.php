<?php
require_once('data-access.php');
require_once('print-helpers.php');

// Functions
function halt($msg) {
    echo '<p><span class="error">' . $msg . '</span></p>';
    exit;
}

// Print header
print_header('Home');

// Home page welcome
echo '<h1>'.htmlspecialchars('Welcome to Bolsillo!').'</h1>';

// Add a Markador
echo '<div>';
echo '<form action="add-markador.php" method="post">';
echo '<span><label for="uri">Add</label>';
echo '<input type="text" id="uri" name="uri" /></span>';
echo '<span><input type="submit" value="Add" /></span>';
echo '</form></div>';

//Bookmarks
$db = null;
try {
    $db = connect_db();
} catch (Exception $e) {
    halt($e->getMessage());
}

$bms = null;
try {
    $bms = get_bm_list($db);
} catch (Exception $e) {
    halt($e->getMessage());
}

$db->close();

echo '<table>';
echo '<thead><tr>';
echo '<th>Markador</th>';
echo '</tr></thead>';
echo '<tbody>';
foreach ($bms as $id => $bm) {
    echo '<tr>';

    echo '<td>';
    echo '<a href="' . $bm . '">' . htmlspecialchars($bm) . '</a>';
    echo '</td>';

    echo '<td>';
    echo '<form action="delete-markador.php" method="POST">';
    echo '<input type="hidden" name="bm_id" id="bm_id" value="'.$id.'" />';
    echo '<span>';
    echo '<input type="submit" value="Delete" />';
    echo '</span>';
    echo '</form>';
    echo '</td>';

    echo '<td>';
    echo '<form action="update-markador.php" method="GET">';
    echo '<input type="hidden" name="id" id="id" value="'.$id.'" />';
    echo '<span>';
    echo '<input type="submit" value="Update" />';
    echo '</span>';
    echo '</form>';
    echo '</td>';

    echo '</tr>';
}

echo '</tbody>';
echo '</table>';

// Print footer
print_footer();

?>
