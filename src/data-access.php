<?php

/*
 * Inserts a new bookmark in database.
 *
 * Takes a SQLite3 connection and a new bookmark
 * URI.
 * Throws exception in case of a row insertion
 * failed.
 */
function add_bm($db, $bm) {
    $st = $db->prepare('INSERT INTO bookmark(uri) VALUES(:uri)');
    if ($st) {
        $br = $st->bindParam(':uri', $bm, SQLITE3_TEXT);
        if ($br) {
            if(!$st->execute()) {
                throw new Exception('Fail to execute prepared statment');
            }
        } else {
            throw new Exception('Unable to bind "uri" prepared statment param');
        }
    } else {
        throw new Exception('Unable to prepare insert statement');
    }
}

/*
 * Connects to 'mysqlitedb.db' database.
 *
 * Returns SQLite3 instance
 * Throws exception if cannot connect to database
 */
function connect_db() {
    $db = new SQLite3('mysqlitedb.db');
    if (!$db) {
        throw new Exception('Unable connect to database');
    }

    return $db;
}

/*
 * Reads all bookmarks from database.
 *
 * Takes SQLite3 DB connection as parameter.
 * Return list where key is bookmark id and
 * value is bookmark itself.
 * Throws exception if unable to read bookmarks.
 */
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

?>
