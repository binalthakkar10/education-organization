<?php

class Mysql {

    /** @var mixed $sock Internal connection handle */
    public $sock;

    /** @var string $host Name of database to connect to */
    public $host;

    /** @var integer $port Portnumber to use when connecting to MySQL */
    public $port;

    /** @var string $user Name of database user used for connection */
    public $user;

    /** @var string $password Password of database user used for connection */
    public $password;

    /** @var string $database Name of schema to be used */
    public $database;

    /** @var integer $num_rows Counts total number of records fetched */
    public $num_rows;

    /** @var integer $querycounter Counts the processed queries against the database */
    public $querycounter;

    /** @var float $querytime Contains the total SQL execution time in microseconds. */
    public $querytime;

    /** @var string $appname Name of Application that uses this class */
    public $appname;

    /** @var string $classversion Version of this class in format VER.REV */
    public $classversion;

    /** @var string $currentQuery Contains the actual query to be processed. */
    public $currentQuery;

    /** @var integer $showError Flag indicates how the class should interact with errors */
    public $showError;

    /** @var integer $debug Flag indicates debug mode of class */
    public $debug;

    /** @var string $SAPI_type The SAPI type of php (used to detect CLI sapi) */
    public $SAPI_type;

    /** @var string $AdminEmail Email Address for the administrator of this project */
    public $AdminEmail;

    /** @var integer $myErrno Error code of last mysql operation (set in Print_Error()) */
    public $myErrno;

    /** @var string $myErrStr Error string of last mysql operation (set in Print_Error()) */
    public $myErrStr;

    /** @var boolean $usePConnect TRUE = Connect() uses Persistant connection, else new one (Default) */
    public $usePConnect;

    /** @var string $character_set Character set to use. */
    public $character_set;

    /** @var string $locale Locale to use for DATE_FORMAT(), DAYNAME() and MONTHNAME() SQL functions. */
    public $locale;

    function __construct($extconfig = '') {
        if ($extconfig == '') {
            
        } else {
            require_once($extconfig);
        }
        $this->classversion = '0.36';

        $this->host = '';
        $this->port = 3306;
        $this->user = '';
        $this->pass = '';
        $this->database = '';
        $this->appname = MYSQLAPPNAME;
        $this->sock = 0;
        $this->querycounter = 0;
        $this->querytime = 0.000;
        $this->currentQuery = '';
        $this->debug = 0;
        $this->myErrno = 0;
        $this->MyErrStr = '';
        $this->AdminEmail = $_SERVER['SERVER_ADMIN']; // Defaults to Webadministrator of Server
        $this->SAPI_type = @php_sapi_name();         // May contain 'cli', in this case disable HTML errors!
        $this->usePConnect = FALSE;                    // Set to TRUE to use Persistant connections
        $this->characterset = '';                       // Will be filled from define MYSQLDB_CHARACTERSET during Connect()
        $this->locale = '';                       // Will be filled from define MYSQLDB_TIME_NAMES during Connect()

        if (!defined('MYSQLAPPNAME')) {
            $this->setErrorHandling(DBOF_SHOW_ALL_ERRORS);
            $this->Print_Error('config.php not found/wrong configured! Please check Class installation!');
        }

        if (defined('DB_ERRORMODE')) {                       // You can set a default behavour for error handling in debdefs.inc.php
            $this->setErrorHandling(DB_ERRORMODE);
        } else {
            $this->setErrorHandling(DBOF_SHOW_NO_ERRORS);   // Default is not to show too much informations
        }

        if (defined('MYSQLDB_ADMINEMAIL')) {
            $this->AdminEmail = MYSQLDB_ADMINEMAIL;         // If set use this address instead of default webmaster
        }

        // Check if user requested persistant connection per default in dbdefs.inc.php

        if (defined('MYSQLDB_USE_PCONNECT') && MYSQLDB_USE_PCONNECT != 0) {
            $this->usePConnect = TRUE;
        }

        // Check if user wants to have set a specific character set with 'SET NAMES <cset>'

        if (defined('MYSQLDB_CHARACTERSET') && MYSQLDB_CHARACTERSET != '') {
            $this->characterset = MYSQLDB_CHARACTERSET;
        }

        // Check if user wants to have set a specific locale with 'SET SET lc_time_names = <locale>;'

        if (defined('MYSQLDB_TIME_NAMES') && MYSQLDB_TIME_NAMES != '') {
            $this->locale = MYSQLDB_TIME_NAMES;
        }
    }

    function Connect($user = '', $pass = '', $host = '', $db = '', $port = 0) {

        if ($this->sock) {
            return($this->sock);
        }
        if ($user != '') {
            $this->user = $user;
        } else {
            $this->user = MYSQLDB_USER;
        }
        if ($pass != '') {
            $this->pass = $pass;
        } else {
            $this->pass = MYSQLDB_PASS;
        }
        if ($host != '') {
            $this->host = $host;
        } else {
            $this->host = MYSQLDB_HOST;
        }
        if ($db != '') {
            $this->database = $db;
        } else {
            $this->database = MYSQLDB_DATABASE;
        }
        if (!$port) {
            if (defined('MYSQLDB_PORT')) {
                $this->port = MYSQLDB_PORT;
            } else {
                $this->port = 3306;
            }
        } else {
            $this->port = $port;
        }
        // Append port number ONLY if hostname doesn't start with a slash (which is a pipe!)
        if (strlen($this->host) >= 1 && $this->host[0] != '/') {
            $this->host . ':' . $this->port;
        }
        $start = $this->getmicrotime();
        $this->printDebug('mysql_connect(' . sprintf("%s/%s@%s", $this->user, $this->pass, $this->host) . ')');
        if ($this->usePConnect == FALSE) {
            if (version_compare('4.3.0', @phpversion()) < 0) {
                $this->sock = @mysql_connect($this->host, $this->user, $this->pass, FALSE);
            } else {
                $this->sock = @mysql_connect($this->host, $this->user, $this->pass);
            }
        } else {    // Persistant connection requested:
            if (version_compare('4.3.0', @phpversion()) < 0) {
                $this->sock = @mysql_pconnect($this->host, $this->user, $this->pass, FALSE);
            } else {
                $this->sock = @mysql_pconnect($this->host, $this->user, $this->pass);
            }
        }
        if (!$this->sock) {
            $this->Print_Error('Connect(): Connection to ' . $this->host . ' failed!');
            return(0);
        }
        if (!@mysql_select_db($this->database, $this->sock)) {
            $this->Print_Error('Connect(): SelectDB(\'' . $this->database . '\') failed!');
            return(0);
        }
        $this->querytime+= ($this->getmicrotime() - $start);
        if ($this->characterset != '') {
            $rc = $this->Query("SET NAMES '" . $this->characterset . "'", MYSQL_NUM, 1);
            if ($rc != 1) {
                $this->Print_Error('Connect(): Error while trying to set character set "' . $this->characterset . '" !!!');
                return(0);
            }
        }

        return($this->sock);
    }

    /**
     * Disconnects from MySQL.
     * You may optionally pass an external link identifier.
     * @param mixed $other_sock Optionally your own connection handle to close, else internal will be used
     * @see mysql_close
     */
    function Disconnect($other_sock = -1) {
        if ($other_sock != -1) {
            @mysql_close($other_sock);
        } else {
            if ($this->sock) {
                @mysql_close($this->sock);
                $this->sock = 0;
            }
        }
        $this->currentQuery = '';
    }

    function Print_Error($ustr = "", $var2dump = "") {
        $errnum = @mysql_errno();
        $errstr = @mysql_error();
        $filename = basename($_SERVER['SCRIPT_FILENAME']);
        $this->myErrno = $errnum;
        $this->myErrStr = $errstr;
        if ($errstr == '') {
            $errstr = 'N/A';
        }
        if ($errnum == '') {
            $errnum = -1;
        }
        @error_log($this->appname . ': Error in ' . $filename . ': ' . $ustr . ' (' . chop($errstr) . ')', 0);
        if ($this->showError == DBOF_RETURN_ALL_ERRORS) {
            return($errnum);      // Return the error number
        }
        $this->SendMailOnError($errnum, $errstr, $ustr);
        $crlf = "\n";
        $space = " ";
        if ($this->SAPI_type != 'cli') {
            $crlf = "<br>\n";
            $space = "&nbsp;";
            echo("<br>\n<div align=\"left\" style=\"background-color: #EEEEEE; color:#000000\" class=\"TB\">\n");
            echo("<font color=\"red\" face=\"Arial, Sans-Serif\"><b>" . $this->appname . ": Database Error occured!  
<a href='javascript:void(0)' onclick='window.history.go(-1)' style='padding-left:30px;'>Back</a></b></font><br>\n<br>\n<code>\n");
        } else {
            echo("\n!!! " . $this->appname . ": Database Error occured !!!\n\n");
        }
        echo($space . "CODE: " . $errnum . $crlf);
        echo($space . "DESC: " . $errstr . $crlf);
        echo($space . "FILE: " . $filename . $crlf);
        if ($this->showError == DBOF_SHOW_ALL_ERRORS) {
            if ($this->currentQuery != "") {
                echo("QUERY: " . $this->currentQuery . $crlf);
            }
            echo($space . "QCNT: " . $this->querycounter . $crlf);
            if ($ustr != '') {
                echo($space . "INFO: " . $ustr . $crlf);
            }
            if ($var2dump != '') {
                echo($space . 'DUMP: ');
                if (is_array($var2dump)) {
                    if ($this->SAPI_type != 'cli') {
                        echo('<pre>');
                        print_r($var2dump);
                        echo("</pre>\n");
                    } else {
                        print_r($var2dump);
                    }
                } else {
                    echo($var2dump . $crlf);
                }
            }
        }
        if ($this->SAPI_type != 'cli') {
            echo("<br>\nPlease inform <a href=\"mailto:" . $this->AdminEmail . "\">" . $this->AdminEmail . "</a> about this problem.");
            echo("</code>\n");
            echo("</div>\n");
            echo("<div align=\"right\"><small>PHP v" . phpversion() . " / MySQL Class v" . $this->classversion . "</small></div>\n");
        } else {
            echo("\nPlease inform " . $this->AdminEmail . " about this problem.\n\nRunning on PHP V" . phpversion() . " / MySQL Class v" . $this->classversion . "\n");
        }
        $this->Disconnect();
    exit;
    }

    function Query($querystring, $resflag = MYSQL_ASSOC, $no_exit = 0) {
        if (!$this->sock) {
            return($this->Print_Error('Query(): No active Connection!', $querystring));
        }
        if ($this->debug) {
            $this->PrintDebug($querystring);
        }
        $this->currentQuery = $querystring;
        if ($this->showError == DBOF_RETURN_ALL_ERRORS) {
            $no_exit = 1;  // Override if user has set master define
        }
        $start = $this->getmicrotime();
        $res = @mysql_query($querystring, $this->sock);
        if ($res == false) {
            if ($no_exit) {
                $reterror = @mysql_errno();
                return($reterror);
            } else {
                return($this->Print_Error("Query('" . $querystring . "') failed!"));
            }
        }
        $this->querycounter++;
        // Check if query requires returning the results or just the result of the query call:
        if (StriStr($querystring, 'SELECT ') ||
                StriStr($querystring, 'SHOW ') ||
                StriStr($querystring, 'EXPLAIN ') ||
                StriStr($querystring, 'DESCRIBE ') ||
                StriStr($querystring, 'OPTIMIZE ') ||
                StriStr($querystring, 'ANALYZE ') ||
                StriStr($querystring, 'CHECK ')
        ) {
            $this->num_rows = mysql_num_rows($res);
            $retdata = @mysql_fetch_array($res, $resflag);
            @mysql_free_result($res);
            $this->querytime+= ($this->getmicrotime() - $start);
            return($retdata);
        } else {
            $this->querytime+= ($this->getmicrotime() - $start);
            return($res);
        }
    }

    function QueryResult($querystring, $no_exit = 0) {
        if (!$this->sock) {
            return($this->Print_Error('QueryResult(): No active Connection!', $querystring));
        }
        if ($this->debug) {
            $this->PrintDebug($querystring);
        }
        $this->currentQuery = $querystring;
        $start = $this->getmicrotime();
        $res = @mysql_query($querystring, $this->sock);
        $this->num_rows = @mysql_num_rows($res);
        $this->querycounter++;
        if ($res == false) {
            if ($no_exit) {
                $reterror = @mysql_errno();
                $this->querytime+= ($this->getmicrotime() - $start);
                return($reterror);
            } else {
                return($this->Print_Error("QueryResult('" . $querystring . "') failed!"));
            }
        }
        $this->querytime+= ($this->getmicrotime() - $start);
        return($res);
    }

    function FetchResult($result, $resflag = MYSQL_ASSOC) {
        if (!$result) {
            return($this->Print_Error('FetchResult(): No valid result handle!'));
        }
        $start = $this->getmicrotime();
        $resar = @mysql_fetch_array($result, $resflag);
        $this->querytime+= ($this->getmicrotime() - $start);
        return($resar);
    }

    function FetchAllResults($querystring) {
        $result = $this->QueryResult($querystring, $no_exit = 0);
        $resultarr = array();
        $i = 0;
        while ($resar = @mysql_fetch_array($result, MYSQL_ASSOC)) {
            foreach ($resar as $key => $val) {
                $resultarr[$i][$key] = $val;
            }
            $i++;
        }
        return $resultarr;
    }

    function FreeResult($result) {
        $this->currentQuery = '';
        $start = $this->getmicrotime();
        $myres = @mysql_free_result($result);
        $this->querytime+= ($this->getmicrotime() - $start);
        return($myres);
    }

    function Version() {
        $weopen = 0;
        if (!$this->sock) {
            $this->connect();
            $weopen = 1;
        }
        $ver = $this->Query('SELECT VERSION()', MYSQL_NUM);
        if ($weopen) {
            $this->Disconnect();
        }
        return($ver[0]);
    }

    function GetQueryCount() {
        return($this->querycounter);
    }

    /**
     * Returns amount of time spend on queries executed by this class.
     * @return float Time in seconds.msecs spent in executin MySQL code.
     */
    function GetQueryTime() {
        return($this->querytime);
    }

    /**
     * Commits current transaction.
     * Note: Requires transactional tables, else does nothing!
     */
    function Commit() {
        if ($this->debug) {
            $this->PrintDebug('COMMIT called');
        }
        $this->Query('COMMIT;');
    }

    /**
     * Rollback current transaction.
     * Note: Requires transactional tables, else does nothing!
     */
    function Rollback() {
        if ($this->debug) {
            $this->PrintDebug('ROLLBACK called');
        }
        $this->Query('ROLLBACK;');
    }

    function SetDebug($state) {
        $this->debug = $state;
    }

    /**
     * Returns the current debug setting.
     * @return integer The debug setting (bitmask)
     * @see SetDebug()
     */
    function GetDebug() {
        return($this->debug);
    }

    /**
     * Handles output according to internal debug flag.
     * @param string $msg The Text to be included in the debug message.
     * @see error_log
     */
    function PrintDebug($msg) {
        if (!$this->debug) {
            return;
        }
        if ($this->SAPI_type != 'cli') {
            $formatstr = "<div align=\"left\" style=\"background-color:#ffffff; color:#000000;\"><pre>DEBUG: %s</pre></div>\n";
        } else {
            $formatstr = "DEBUG: %s\n";
        }
        if ($this->debug & DBOF_DEBUGSCREEN) {
            @printf($formatstr, $msg);
        }
        if ($this->debug & DBOF_DEBUGFILE) {
            @error_log('DEBUG: ' . $msg, 0);
        }
    }

    /**
     * Returns version of this class.
     * @return string The version of this class.
     */
    function GetClassVersion() {
        return($this->classversion);
    }

    /**
     * Returns last used auto_increment id.
     * @param mixed $extsock Optionally an external MySQL socket to use. If not given the internal socket is used.
     * @return integer The last automatic insert id that was assigned by the MySQL server.
     * @see mysql_insert_id
     */
    function LastInsertId($extsock = -1) {
        if ($extsock == -1) {
            return(@mysql_insert_id($this->sock));
        } else {
            return(@mysql_insert_id($extsock));
        }
    }

    /**
     * Returns count of affected rows by last DML operation.
     * @param mixed $extsock Optionally an external MySQL socket to use. If not given the internal socket is used.
     * @return integer The number of affected rows by previous DML operation.
     * @see mysql_affected_rows
     */
    function AffectedRows($extsock = -1) {
        if ($extsock == -1) {
            return(@mysql_affected_rows($this->sock));
        } else {
            return(@mysql_affected_rows($extsock));
        }
    }

    function ConvertMySQLDate($mysqldate, $fmtstring) {
        $dt = explode(' ', $mysqldate);  // Split in date/time
        $dp = explode('-', $dt[0]);                                  // Split date
        $tp = explode(':', $dt[1]);                                  // Split time
        $ts = mktime(intval($tp[0]), intval($tp[1]), intval($tp[2]), intval($dp[1]), intval($dp[2]), intval($dp[0]));    // Create time stamp
        if ($fmtstring == '') {
            $fmtstring = '%c';
        }
        return(strftime($fmtstring, $ts));
    }

    function getmicrotime() {
        list($usec, $sec) = explode(" ", microtime());
        return ((float) $usec + (float) $sec);
    }

    function setErrorHandling($val) {
        $this->showError = $val;
    }

    function GetConnectionHandle() {
        return($this->sock);
    }

    /**
     * Allows to set internal socket to external value.
     * @param mixed New socket handle to set (as returned from mysql_connect())
     * @see mysql_connect
     * @since 0.32
     */
    function SetConnectionHandle($extsock) {
        $this->sock = $extsock;
    }

    function SendMailOnError($merrno, $merrstr, $uerrstr) {
        if (!defined('MYSQLDB_SENTMAILONERROR') || MYSQLDB_SENTMAILONERROR == 0 || $this->AdminEmail == '') {
            return;
        }
        $server = $_SERVER['SERVER_NAME'] . " (" . $_SERVER['SERVER_ADDR'] . ")";
        if ($server == ' ()' || $server == '') {
            $server = 'n/a';
        }
        $uagent = $_SERVER['HTTP_USER_AGENT'];
        if ($uagent == '') {
            $uagent = 'n/a';
        }
        $clientip = $_SERVER['REMOTE_ADDR'] . " (" . @gethostbyaddr($_SERVER['REMOTE_ADDR']) . ")";
        if ($clientip == ' ()' || $clientip == '') {
            $clientip = 'n/a';
        }
        $message = "MySQLDB Class v" . $this->classversion . ": Error occured on " . date('r') . " !!!\n\n";
        $message.= "      APPLICATION: " . $this->appname . "\n";
        $message.= "  AFFECTED SERVER: " . $server . "\n";
        $message.= "       USER AGENT: " . $uagent . "\n";
        $message.= "       PHP SCRIPT: " . $_SERVER['SCRIPT_FILENAME'] . "\n";
        $message.= "   REMOTE IP ADDR: " . $clientip . "\n";
        $message.= "    DATABASE DATA: " . $this->user . " @ " . $this->host . "\n";
        $message.= "SQL ERROR MESSAGE: " . $merrstr . "\n";
        $message.= "   SQL ERROR CODE: " . $merrno . "\n";
        $message.= "    QUERY COUNTER: " . $this->querycounter . "\n";
        $message.= "         INFOTEXT: " . $uerrstr . "\n";
        if ($this->currentQuery != '') {
            $message.= "        SQL QUERY:\n";
            $message.= "------------------------------------------------------------------------------------\n";
            $message.= $this->currentQuery . "\n";
        }
        $message.= "------------------------------------------------------------------------------------\n";
        if (defined('MYSQLDB_MAIL_EXTRAARGS') && MYSQLDB_MAIL_EXTRAARGS != '') {
            @mail($this->AdminEmail, 'MySQLDB Class v' . $this->classversion . ' ERROR #' . $merrno . ' OCCURED!', $message, MYSQLDB_MAIL_EXTRAARGS);
        } else {
            @mail($this->AdminEmail, 'MySQLDB Class v' . $this->classversion . ' ERROR #' . $merrno . ' OCCURED!', $message);
        }
    }

    /**
     * Retrieve last mysql error number.
     * @param mixed $other_sock Optionally your own connection handle to check, else internal will be used
     * @return integer The MySQL error number of the last operation
     * @see mysql_errno
     * @since 0.33
     */
    function GetErrno($other_sock = -1) {
        if ($other_sock == -1) {
            if (!$this->sock) {
                return($this->myErrno);
            } else {
                return(@mysql_errno($this->sock));
            }
        } else {
            if (!$other_sock) {
                return($this->myErrno);
            } else {
                return(@mysql_errno($other_sock));
            }
        }
    }

    function GetErrorText($other_sock = -1) {
        if ($other_sock == -1) {
            if (!$this->sock) {
                return($this->myErrStr);
            } else {
                return(@mysql_error($this->sock));
            }
        } else {
            if (!$other_sock) {
                return($this->myErrStr);
            } else {
                return(@mysql_error($other_sock));
            }
        }
    }

    function setPConnect($conntype) {
        if (is_bool($conntype) == FALSE) {
            return($this->usePConnect);
        }
        $oldtype = $this->usePConnect;
        $this->usePConnect = $conntype;
        return($oldtype);
    }

    function EscapeString($str) {
        $data = $str;
        if (get_magic_quotes_gpc()) {
            $data = stripslashes($data);
        }
        $link = get_resource_type($this->sock);
        if ($this->sock && substr($link, 0, 5) == 'mysql') {
            return(mysql_real_escape_string($data, $this->sock));
        } else {
            return(mysql_escape_string($data));
        }
    }

    function set_TimeNames($locale) {
        $rc = $this->Query("SET lc_time_names='" . $locale . "'", MYSQL_NUM, 1);
        if ($rc != 1) {
            $this->Print_Error('set_TimeNames(): Error while trying to set lc_time_names to "' . $locale . '" !!!');
            return(0);
        }
        $this->locale = $locale;
        return(1);
    }

    function get_TimeNames() {
        $data = $this->Query("SELECT @@lc_time_names", MYSQL_NUM, 1);
        if (is_array($data) == false) {
            $this->Print_Error('get_TimeNames(): Error while trying to retrieve the lc_time_names variable !!!');
            return(0);
        }
        return($data[0]);
    }

    function set_CharSet($charset) {
        $rc = $this->Query("SET NAMES '" . $charset . "'", MYSQL_NUM, 1);
        if ($rc != 1) {
            $this->Print_Error('set_Names(): Error while trying to perform SET NAMES "' . $locale . '" !!!');
            return(0);
        }
        $this->character_set = $charset;
        return(1);
    }

    function get_CharSet() {
        $retarr = array();
        $stmt = $this->QueryResult("SHOW VARIABLES LIKE 'character_set%'", 1);
        while ($d = $this->FetchResult($stmt, MYSQL_NUM)) {
            array_push($retarr, $d);
        }
        $this->FreeResult($stmt);
        return($retarr);
    }

    function get_Field_Name($tablename) {
        $columns = array();
        $rows = $this->FetchAllResults("SHOW COLUMNS FROM " . $tablename);
        foreach ($rows as $k => $v) {
            if (!in_array($v['Field'], $columns)) {
                $columns[] = $v['Field'];
            }
        }
        return $columns;
    }

    function Insert($tablename, $fields) {
        foreach ($fields as $k => $v) {
            $itfdatainfo[$k] = $this->EscapeString($v);
        }
        $fields = $this->get_Field_Name($tablename);
        foreach ($itfdatainfo as $k => $v) {
            if (!in_array($k, $fields)) {
                unset($itfdatainfo[$k]);
            }
        }
        $itfkeys = array_keys($itfdatainfo);
        $itfvalues = array_values($itfdatainfo);
        $sql = "INSERT INTO " . $this->EscapeString($tablename) . " (" . implode(',', $itfkeys) . ") VALUES('" . implode("','", $itfvalues) . "')";
        $this->Query($sql);
        return $this->LastInsertId();
    }

    function Modify($tablename, $fields = array(), $conditions = array()) {

        $itffields = $this->get_Field_Name($tablename);
//                echo "<pre>";
//                print_r($itffields);
//                echo "_______________<br>";
//                print_r($fields);
//		
        $itfupdate = '';
        foreach ($fields as $k => $v) {

            if (in_array($k, $itffields)) {
                $k . "=" . $itffields;
                $itfupdate .= $k . "='" . $this->EscapeString($v) . "', ";
            }
        }

        $itfupdate = substr($itfupdate, 0, strrpos($itfupdate, ','));
        $sql = "UPDATE " . $this->EscapeString($tablename) . " SET " . $itfupdate;

        if (count($conditions) > 0) {
            $condstr = '';
            foreach ($conditions as $k => $datas)
                $condstr.=$k . "='" . $datas . "' and ";
            $condstr = substr($condstr, 0, -4);
            $sql.=" where " . $condstr;
        }


        $this->Query($sql);
        return $this->AffectedRows();
    }

//function Modify($tablename,$fields=array(),$conditions=array())
//	{
//	       $itffields = $this->get_Field_Name($tablename);
//                echo "<pre>";
//                print_r($itffields);
//                echo "_______________<br>";
//                print_r($fields);
//		
//		$itfupdate = '';
////		foreach($fields as $k=>$v) 
//		{
//                                        
//			
//		}
//		$itfupdate = substr($itfupdate, 0, strrpos($itfupdate, ','));
//		echo $sql = "UPDATE ".$this->EscapeString($tablename)." SET ".$itfupdate;
//		
//		if(count($conditions)>0)
//		{		
//			$condstr='';
//			foreach($conditions as $k=>$datas)
//			$condstr.=$k."='".$datas."' and ";
//			 $condstr=substr($condstr,0,-4);
//			 $sql.=" where ".$condstr;die;
//		}
//		
//                
//                $tot=count($fields); 
//                $i=0;
//		while($i<$tot){
//                 echo $sql="update its_debate set last_name ='". $fields['last_name'][$i]."where".$itffields['id']."=".$fields['id']."'";
//                  $i++;      
//                }
//                 die;   $this->Query($sql);
//		return $this->AffectedRows();
    //}
}

// EOF
?>