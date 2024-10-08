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

echo '<table>';
echo '<thead><tr>';
echo '<th>Markador</th>';
echo '</tr></thead>';
echo '<tbody>';
foreach ($bms as $id => $bm) {
    echo '<tr>';
    echo '<td><a href="' . $bm . '">' . htmlspecialchars($bm) . '</a></td>';
    echo '<td><span><form action="delete-markador.php" method="POST">';
    echo '<input type="hidden" name="bm_id" id="bm_id" value="'.$id.'" />';
    echo '<span><input type="submit" value="Delete" /></span>';
    echo '</form></span></td>';
    echo '<td><span><a href="/update-markador.php?id='.$id.'">Update</a></span></td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';

$db->close();

// Print footer
print_footer();

?>
