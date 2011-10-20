<?php

//a single row is an object
//more than one rows are arrays of objects
//Database class file

class Database {

    var $username;
    var $password;
    var $database;
    var $connection_id;
    var $query;
    var $query_id;
    var $result;
    var $affected_rows;
    var $num_rows;

    function __construct($server=NULL, $username=NULL, $password=NULL, $database=NULL) {

        //Default settings if arguments are not passed while creating the object
        if ($_SERVER['HTTP_HOST'] != "localhost") {
            //Live
            if (!$server)
                $server = "remotehost.com";
            if (!$username)
                $username = "remoteuser";
            if (!$password)
                $password = "remotepassowrd";
        } else {
            //For localhost
            if (!$server)
                $server = "localhost";
            if (!$username)
                $username = "root";
            if (!$password)
                $password = "";
        }
        $this->server = $server;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
    }

    function connect() {
        $this->connection_id = mysql_connect($this->server, $this->username, $this->password) or $this->oops("Connecting to the database on " . $this->server . " failed!");
        mysql_select_db($this->database) or $this->oops("Selecting the database '" . $this->database . "' failed!");
    }

    function changeDb($database) {
        mysql_select_db($database, $this->connection_id);
        $this->database = $database;
    }

    function escape($text) {
        //return mysql_real_escape_string($text, $this->connection_id);
        if ( get_magic_quotes_gpc() ) {
			$text = stripslashes($text);
		}
		
		if ( !is_numeric($text) ) {
			
			$text = mysql_real_escape_string($text);
			
		}
		
		return $text;
    }

    function disconnect() {
        if (!mysql_close($this->connection_id)) {
            $this->oops("Connection close failed.");
        }
    }

    function query($sql) {
        $this->query_id = mysql_query($sql, $this->connection_id) or $this->oops("Query:$sql failed!");
        $this->affected_rows = mysql_affected_rows($this->connection_id);
        if (gettype($this->query_id)=="resourceboolean") $this->num_rows = mysql_num_rows($this->query_id);
        //else $this->affected_rows = mysql_affected_rows($this->query_id);
        $this->query = $sql;
        return $this->query_id;
    }

    #-#############################################
# desc: does a query, fetches the first row only, frees resultset
# param: (MySQL query) the query to run on server
# returns: array of fetched results
#-#query_first()
#-#############################################
# desc: fetches and returns results one line at a time
# param: query_id for mysql run. if none specified, last used
# return: (array) fetched record(s)

    public function fetchNextRow($query_id=-1) {
        // retrieve row
        if ($query_id != -1) {
            $this->query_id = $query_id;
        }

        if (isset($this->query_id)) {
            $record = mysql_fetch_object($this->query_id);
        } else {
            $this->oops("Invalid query_id: <b>$this->query_id</b>. Records could not be fetched.");
        }

        return $record;
    }

#-#fetch()
#-#fetch()
#-#############################################
# desc: returns all the results (not one row)
# param: (MySQL query) the query to run on server
# returns: assoc array of ALL fetched results

    public function fetchRows($sql) {
        $query_id = $this->query($sql);
        $rows = array();

        while ($row = mysql_fetch_object($query_id)) {
            $rows[] = $row;
        }

        //$this->free_result($query_id);
        return $rows;
    }

    /*
     * fetches a single row as object
     */

    public function fetchFirstRow($sql) {
        $r = $this->fetchRows(str_replace(";", "", $sql) . " LIMIT 1");
        if (isset($r[0])) return ($r[0]); else return 0;
    }

    public function countOccurences($table, $field, $value){
        $r=$this->fetchRows("SELECT * from $table WHERE $field='$value'");
        //return count($r);
        return count($r);
    }

#-#fetch_array()
#-#############################################
# desc: does an update query with an array
# param: table, assoc array with data (not escaped), where condition (optional. if none given, all records updated)
# returns: (query_id) for fetching results etc

    public function update($table, $data, $where='1') {
        $q = "UPDATE `$table` SET ";

        foreach ($data as $key => $val) {
            if (strtolower($val) == 'null')
                $q.= "`$key` = NULL, ";
            elseif (strtolower($val) == 'now()')
                $q.= "`$key` = NOW(), ";
            elseif (preg_match("/^increment\((\-?\d+)\)$/i", $val, $m))
                $q.= "`$key` = `$key` + $m[1], ";
            else
                $q.= "`$key`='" . $this->escape($val) . "', ";
        }

        $q = rtrim($q, ', ') . ' WHERE ' . $where . ';';

        return $this->query($q);
    }

#-#update()
#-#############################################
# desc: does an insert query with an array
# param: table, assoc array with data (not escaped)
# returns: id of inserted record, false if error

    public function insert($table, $data) {
        $q = "INSERT INTO `$table` ";
        $v = '';
        $n = '';

        foreach ($data as $key => $val) {
            $n.="`$key`, ";
            if (strtolower($val) == 'null')
                $v.="NULL, ";
            elseif (strtolower($val) == 'now()')
                $v.="NOW(), ";
            else
                $v.= "'" . $this->escape($val) . "', ";
        }

        $q .= "(" . rtrim($n, ', ') . ") VALUES (" . rtrim($v, ', ') . ");";

        if ($this->query($q)) {
            return mysql_insert_id($this->link_id);
        }
        else
            return false;
    }

#-#insert()
#-#############################################
# desc: throw an error message
# param: [optional] any custom error to display

    function oops($msg='') {
?>
        <table align="center" border="1" cellspacing="0" style="background:white;color:black;width:80%;">
            <tr><th colspan=2>Database Error</th></tr>
            <tr><td align="right" valign="top">Message:</td><td><?php echo $msg; ?></td></tr>
<?php echo '<tr><td align="right" valign="top" nowrap>MySQL Error:</td><td>' . mysql_error() . '</td></tr>'; ?>

            <tr><td align="right">Date:</td><td><?php
        //date_default_timezone_set("GMT");
        error_reporting(0);
        echo date("l, F j, Y \a\\t g:i:s A");
        error_reporting(1)
?></td></tr>

    <?php if (!empty($_SERVER['REQUEST_URI']))
            echo '<tr><td align="right">Script:</td><td><a href="' . $_SERVER['REQUEST_URI'] . '">' . $_SERVER['REQUEST_URI'] . '</a></td></tr>'; ?>
<?php if (!empty($_SERVER['HTTP_REFERER']))
            echo '<tr><td align="right">Referer:</td><td><a href="' . $_SERVER['HTTP_REFERER'] . '">' . $_SERVER['HTTP_REFERER'] . '</a></td></tr>'; ?>
</table>
            <?php
        }

#-#oops()
    }
            ?>