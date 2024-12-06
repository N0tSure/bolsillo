<?php

/**
 * Represents prepared statement parameter.
 */
class PrepStmtParameter
{
    private $name;
    private $type;
    private $value;

    /**
     * Creates a PrepStmtParameter.
     *
     * @param $name string for example, ':email'
     * @param $type int SQLite3 parameter type
     * @param $value - any type
     */
    public function __construct($name, $type, $value)
    {
        $this->name = $name;
        $this->type = $type;
        $this->value = $value;
    }

    public function __toString()
    {
        return "PreparedStatementParameter[name='$this->name';type='$this->type';value='$this->value']";
    }

    function bind(SQLite3Stmt $stmt)
    {
        return $stmt->bindValue($this->name, $this->value, $this->type);
    }

}

/**
 * This class encapsulates basic DAO functional.
 */
abstract class Dao
{
    private $connection;

    public function __construct()
    {
        $this->connection = new SQLite3('mysqlitedb.db', SQLITE3_OPEN_READWRITE, '');
        $this->connection->enableExceptions(true);
    }

    public function __destruct()
    {
        $this->connection->close();
    }

    /**
     * Returns ready connection.
     *
     * @return SQLite3 connection
     */
    protected function getConnection()
    {
        return $this->connection;
    }

    /**
     * This method prepares SQLite3Stmt, binds parameters as values, and executes
     * statement.
     *
     * @param $sql string SQL command or query
     * @param $params array of PrepStmtParameter
     * @return false|SQLite3Result
     */
    protected function executePrepStmt($sql, $params)
    {
        $s = $this->connection->prepare($sql);
        if ($s):
            foreach ($params as $p):
                if(!$p->bind($s)):
                    error_log("Unable to bind parameter: [$p]");
                endif;
            endforeach;

            return $s->execute();
        endif;

        return false;
    }
}

/**
 * DAO for Marcadores.
 */
class MarcadorDao extends Dao
{

    /**
     * Returns array of Marcadores.
     *
     * @param $user int User identifier
     * @return false|array Array of Marcadores or false if nothing to return
     */
    public function getMarcadores($user)
    {
        $rs = $this->executePrepStmt(
            'SELECT id, uri FROM bookmark WHERE user = :user',
            array(new PrepStmtParameter(':user', SQLITE3_INTEGER, $user))
        );

        $result = false;
        if ($rs):
            $result = array();
            while ($cs = $rs->fetchArray(SQLITE3_ASSOC))
            {
                $result[$cs['id']] = $cs['uri'];
            }
        endif;

        return $result;
    }

}

/**
 * DAO for Users.
 */
class UserDao extends Dao
{

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
        $rs = $this->executePrepStmt(
            'SELECT id, username as "name", email FROM users ' .
            'WHERE email = :email COLLATE NOCASE AND password = :password',
            array(
                new PrepStmtParameter(':email', SQLITE3_TEXT, $email),
                new PrepStmtParameter(':password', SQLITE3_TEXT, $passwd),
            )
        );

        if ($rs):
            $result = $rs->fetchArray(SQLITE3_ASSOC);
        endif;

        return $result;
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
