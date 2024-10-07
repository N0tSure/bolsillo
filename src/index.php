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
// Functions
function connect_db() {
    $db = new SQLite3('mysqlitedb.db');
    if (!$db) {
        throw new Exception('Unable connect to database');
    }

    return $db;
}

function get_bm_list($db) {
    $rt = array();
    $cr = $db->query('SELECT * FROM bookmark');
    if ($cr) {
        while ($r = $cr->fetchArray(SQLITE3_ASSOC)) {
            $rt[$r['id']] = $r['uri'];
        }
    } else {
        throw new Exception('Unable to read bookmarks');
    }

    return $rt;
}

function halt($msg) {
    echo '<p><span class="error">' . $msg . '</span></p>';
    exit;
}

// Home page welcome
$header = 'Welcome to Bolsillo!';

echo '<h1>' . htmlspecialchars($header) . '</h1>';

//

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
