<?php

class UserDao
{
    private $connection;

    /**
     * Finds active user which email and password hash matching with provided.
     *
     * @param string $email User email (login)
     * @param string $passwd User password MD5 hash
     * @return false|array Array with user id, name and email, or false
     */
    public function findUser($email, $passwd)
    {
        $result = false;
        $st = $this->connection->prepare(
            'SELECT id, username as "name", email FROM users ' .
            'WHERE email = :email COLLATE NOCASE AND password = :password'
        );

        if ($st):
            foreach (array(':email' => $email, ':password' => $passwd) as $param => $value):
                $br = $st->bindValue($param, $value);
                if (!$br):
                    error_log("Unable to bind parameter: [$param] = [$value]");
                endif;
            endforeach;
            $rs = $st->execute();
            if ($rs):
                $result = $rs->fetchArray(SQLITE3_ASSOC);
            endif;
        endif;

        return $result;
    }

    public function __construct()
    {
        $this->connection = new SQLite3('mysqlitedb.db', SQLITE3_OPEN_READWRITE, '');
        $this->connection->enableExceptions(true);
    }

    public function __destruct()
    {
        $this->connection->close();
    }
}

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
                    _do_bind_param($st, $name, $val[0], $val[1]);
                } else {
                    throw new Exception('Wrong amount of params passed');
                }
            }
            $result = $st->execute();
            if ($result) {
                return $result;
            } else {
                throw new Exception('Fail to execute prepared statment');
            }
        } else {
            throw new Exception('Unable to prepare statement: "'.$query.'"');
        }
    } else {
        throw new Exception ('Params wasnt passed');
    }
}

function _do_bind_param($st, $name, $value, $type) {
    $v = addslashes($value);
    if (!$st->bindParam($name, $v, $type)) {
        throw new Exception('Unable to bind prepared statement param: '.$name.', with value "'.$v.'", type: '.$type);
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
    $params = array(':uri' => array($bm, SQLITE3_TEXT));
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

/**
 * Returns a Bookmark.
 *
 * :param $db SQLite3 db connection
 * :param $id Bookmark id
 * Return an array having attributes of Bookmark.
 * Throws exception
 */
function get_bm($db, $id) {
    $p = array(':id' => array($id, SQLITE3_INTEGER));
    $rs = _do_execute_prep_st($db, 'SELECT * FROM bookmark WHERE id = :id', $p);
    $result = $rs->fetchArray(SQLITE3_ASSOC);
    $rs->finalize();

    if ($result) {
        return $result;
    } else {
        throw new Exception('Bookmark wasnt find');
    }
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

/**
 * Updates a Bookmark.
 *
 * :param db SQLite3 db connection
 * :param id Bookmark id
 * :param uri Bookmark value
 * :throws Exception in case of error
 */
function update_bm($db, $id, $uri) {
    $bm_p = array(
        ':id' => array($id, SQLITE3_INTEGER),
        ':uri' => array($uri, SQLITE3_TEXT)
    );
    _do_execute_prep_st($db, 'UPDATE bookmark SET uri = :uri WHERE id = :id', $bm_p);
}

?>
