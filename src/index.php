<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    span.error {
        background: #F5B7B1;
    }
</style>
<title>
<?= 'Bolsillo :: ' . htmlspecialchars('Home'); ?>
</title>
</head>
<body>

<?php
require_once('data-access.php');

// Functions
function halt($msg) {
    echo '<p><span class="error">' . $msg . '</span></p>';
    exit;
}

// Home page welcome
$header = 'Welcome to Bolsillo!';

echo '<h1>' . htmlspecialchars($header) . '</h1>';

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
echo '<th>Markador</th><th>Delete?</th>';
echo '</tr></thead>';
echo '<tbody>';
foreach ($bms as $id => $bm) {
    echo '<tr>';
    echo '<td><a href="' . $bm . '">' . htmlspecialchars($bm) . '</a></td>';
    echo '<td><span>yes</span></td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';

$db->close();
?>

</body>
</html>
