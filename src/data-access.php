<?php

/*
 * Creates and executes prepared statement from
 * provided query.
 *
 * Accept parameters in for of an Array, where
 * key is name of parameter (placeholder). Value
 * must be array of value and SQL type of it.
 *
 * :param $db SQLite3 connection instance
 * :param $query SQL query to prepare
 * :param $params parameters to bind
 * :throws exception in case of unable to prepare
 * or execute statement
 */
function _do_execute_prep_st($db, $query, $params) {
    if (!empty($params) && count($params) > 0) {
        $st = $db->prepare($query);
        if ($st) {
            foreach ($params as $name => $val) {
                if (count($val) == 2) {
                    $v = addslashes($val[0]);
                    $t = $val[1];
                    $r = $st->bindParam($name, $v, $t);
                    if (!$r) {
                        throw new Exception('Unable to bind prepared statement param: '.$name.', with value "'.$v.'", type: '.$t);
                    }
                } else {
                    throw new Exception('Wrong amount of params passed');
                }
            }
            if (!$st->execute()) {
                throw new Exception('Fail to execute prepared statment');
            }
        } else {
            throw new Exception('Unable to prepare statement: "'.$query.'"');
        }
    } else {
        throw new Exception ('Params wasnt passed');
    }
}

/*
 * Inserts a new bookmark in database.
 *
 * Takes a SQLite3 connection and a new bookmark
 * URI.
 * Throws exception in case of a row insertion
 * failed.
 */
function add_bm($db, $bm) {
    $params = array(
        ':uri' => array($bm, SQLITE3_TEXT)
    );

    _do_execute_prep_st($db, 'INSERT INTO bookmark(uri) VALUES(:uri)', $params);
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
 * Deletes bookmark from database.
 *
 * Takes instance of SQLite3 connection.
 * Takes id of bookmark.
 * Throws exception whether unable to delete.
 */
function delete_bm($db, $id) {
    $params = array(':id' => array($id, SQLITE3_INTEGER));
    _do_execute_prep_st($db, 'DELETE FROM bookmark WHERE id = :id', $params);
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
