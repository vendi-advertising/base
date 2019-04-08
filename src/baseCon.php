<?php

declare(strict_types=1);

namespace Vendi\BASE;

use Vendi\BASE\Exceptions\UnknownDatabaseException;

class baseCon
{
    public $DB;
    public $DB_type;
    public $DB_name;
    public $DB_host;
    public $DB_port;
    public $DB_username;
    public $lastSQL;
    public $version;
    public $sql_trace;

    public function __construct($type)
    {
        $this->DB_type = $type;
    }

    public function baseDBConnect($method, $database, $host, $port, $username, $password, $force = 0)
    {
        global $archive_dbname, $archive_host, $archive_port, $archive_user, $archive_password, $debug_mode;

        // Check archive cookie to see if they want to be using the archive tables
        // and check - do we force to use specified database even if archive cookie is set
        if ((1 == @$_COOKIE['archive']) && (1 != $force)) {
            // Connect to the archive tables
            if ($debug_mode > 0) {
                echo "<BR><BR>\n" . __FILE__ . ':' . __LINE__ . ": DEBUG: Connecting to archive db.<BR><BR>\n\n";
            }

            if (DB_CONNECT == $method) {
                $this->baseConnect($archive_dbname, $archive_host, $archive_port, $archive_user, $archive_password);
            } else {
                $this->basePConnect($archive_dbname, $archive_host, $archive_port, $archive_user, $archive_password);
            }
        } else {
            // Connect to the main alert tables
            if ($debug_mode > 0) {
                echo "<BR><BR>\n" . __FILE__ . ':' . __LINE__ . ": DEBUG: Connecting to alert db.<BR><BR>\n\n";
            }

            if (DB_CONNECT == $method) {
                $this->baseConnect($database, $host, $port, $username, $password);
            } else {
                $this->basePConnect($database, $host, $port, $username, $password);
            }
        }
    }

    public function baseConnect($database, $host, $port, $username, $password)
    {
        global $sql_trace_mode, $sql_trace_file;

        $this->DB = NewADOConnection();
        $this->DB_name = $database;
        $this->DB_host = $host;
        $this->DB_port = $port;
        $this->DB_username = $username;

        if ($sql_trace_mode > 0) {
            $this->sql_trace = fopen($sql_trace_file, 'a');
            if (!$this->sql_trace) {
                ErrorMessage(_ERRSQLTRACE . " '" . $sql_trace_file . "'");
                die();
            }
        }

        $db = $this->DB->Connect((('' == $port) ? $host : ($host . ':' . $port)),
                               $username, $password, $database);

        if (!$db) {
            $tmp_host = ('' == $port) ? $host : ($host . ':' . $port);
            echo '<P><B>' . _ERRSQLCONNECT . ' </B>' .
             $database . '@' . $tmp_host . _ERRSQLCONNECTINFO;

            echo $this->baseErrorMessage();
            die();
        }

        /* Set the database schema version number */
        $sql = 'SELECT vseq FROM schema';
        if (DatabaseTypes::MYSQL == $this->DB_type) {
            $sql = 'SELECT vseq FROM `schema`';
        }
        if (DatabaseTypes::MSSQL == $this->DB_type) {
            $sql = 'SELECT vseq FROM [schema]';
        }

        $result = $this->DB->Execute($sql);
        if ('' != $this->baseErrorMessage()) {
            $this->version = 0;
        } else {
            $myrow = $result->fields;
            $this->version = $myrow[0];
            $result->Close();
        }

        if ($sql_trace_mode > 0) {
            fwrite($this->sql_trace,
              "\n--------------------------------------------------------------------------------\n");
            fwrite($this->sql_trace, 'Connect [' . $this->DB_type . '] ' . $database . '@' . $host . ':' . $port . ' as ' . $username . "\n");
            fwrite($this->sql_trace, '[' . date('M d Y H:i:s', time()) . '] ' . $_SERVER['SCRIPT_NAME'] . ' - db version ' . $this->version);
            fwrite($this->sql_trace,
              "\n--------------------------------------------------------------------------------\n\n");
            fflush($this->sql_trace);
        }

        return $db;
    }

    public function basePConnect($database, $host, $port, $username, $password)
    {
        global $sql_trace_mode, $sql_trace_file;

        $this->DB = NewADOConnection();
        $this->DB_name = $database;
        $this->DB_host = $host;
        $this->DB_port = $port;
        $this->DB_username = $username;

        if ($sql_trace_mode > 0) {
            $this->sql_trace = fopen($sql_trace_file, 'a');
            if (!$this->sql_trace) {
                ErrorMessage(_ERRSQLTRACE . " '" . $sql_trace_file . "'");
                die();
            }
        }

        $db = $this->DB->PConnect((('' == $port) ? $host : ($host . ':' . $port)),
                               $username, $password, $database);

        if (!$db) {
            $tmp_host = ('' == $port) ? $host : ($host . ':' . $port);
            echo '<P><B>' . _ERRSQLPCONNECT . ' </B>' .
             $database . '@' . $tmp_host . _ERRSQLCONNECTINFO;

            echo $this->baseErrorMessage();
            die();
        }

        /* Set the database schema version number */
        $sql = 'SELECT vseq FROM schema';
        if (DatabaseTypes::MSSQL == $this->DB_type) {
            $sql = 'SELECT vseq FROM [schema]';
        }
        if (DatabaseTypes::MYSQL == $this->DB_type) {
            $sql = 'SELECT vseq FROM `schema`';
        }

        $result = $this->DB->Execute($sql);
        if ('' != $this->baseErrorMessage()) {
            $this->version = 0;
        } else {
            $myrow = $result->fields;
            $this->version = $myrow[0];
            $result->Close();
        }

        if ($sql_trace_mode > 0) {
            fwrite($this->sql_trace,
              "\n--------------------------------------------------------------------------------\n");
            fwrite($this->sql_trace, 'PConnect [' . $this->DB_type . '] ' . $database . '@' . $host . ':' . $port . ' as ' . $username . "\n");
            fwrite($this->sql_trace, '[' . date('M d Y H:i:s', time()) . '] ' . $_SERVER['SCRIPT_NAME'] . ' - db version ' . $this->version);
            fwrite($this->sql_trace,
              "\n--------------------------------------------------------------------------------\n\n");
            fflush($this->sql_trace);
        }

        return $db;
    }

    public function baseClose()
    {
        $this->DB->Close();
    }

    public function baseExecute($sql, $start_row = 0, $num_rows = -1, $die_on_error = true)
    {
        global $debug_mode, $sql_trace_mode;

        /* ** Begin DB specific SQL fix-up ** */
        if (DatabaseTypes::MSSQL == $this->DB_type) {
            $sql = eregi_replace("''", 'NULL', $sql);
        }

        if (DatabaseTypes::ORACLE == $this->DB_type) {
            if (!strpos($sql, 'TRIGGER')) {
                if (';' == substr($sql, strlen($sql) - 1, strlen($sql))) {
                    $sql = substr($sql, 0, strlen($sql) - 1);
                }
            }
        }

        $this->lastSQL = $sql;
        $limit_str = '';

        /* Check whether need to add a LIMIT / TOP / ROWNUM clause */
        if (-1 == $num_rows) {
            $rs = new baseRS($this->DB->Execute($sql), $this->DB_type);
        } else {
            if (DatabaseTypes::MYSQL == $this->DB_type) {
                $rs = new baseRS($this->DB->Execute($sql . ' LIMIT ' . $start_row . ', ' . $num_rows),
                             $this->DB_type);
                $limit_str = ' LIMIT ' . $start_row . ', ' . $num_rows;
            } elseif (DatabaseTypes::ORACLE == $this->DB_type) {
                $rs = new baseRS($this->DB->Execute($sql),
                             $this->DB_type);
                $limit_str = ' LIMIT ' . $start_row . ', ' . $num_rows;
            } elseif (DatabaseTypes::POSTGRES == $this->DB_type) {
                $rs = new baseRS($this->DB->Execute($sql . ' LIMIT ' . $num_rows . ' OFFSET ' . $start_row),
                             $this->DB_type);
                $limit_str = ' LIMIT ' . $num_rows . ' OFFSET ' . $start_row;
            }

            /* Databases which do not support LIMIT (e.g. MS SQL) natively must emulated it */
            else {
                $rs = new baseRS($this->DB->Execute($sql), $this->DB_type);
                $i = 0;
                while (($i < $start_row) && $rs) {
                    if (!$rs->row->EOF) {
                        $rs->row->MoveNext();
                    }
                    ++$i;
                }
            }
        }

        if ($sql_trace_mode > 0) {
            fputs($this->sql_trace, $sql . "\n");
            fflush($this->sql_trace);
        }

        if ((!$rs || '' != $this->baseErrorMessage()) && $die_on_error) {
            echo '</TABLE></TABLE></TABLE>
               <FONT COLOR="#FF0000"><B>' . _ERRSQLDB . '</B>' . ($this->baseErrorMessage()) . '</FONT>' .
               '<P><PRE>' . ($debug_mode > 0 ? ($this->lastSQL) . $limit_str : '') . '</PRE><P>';
            die();
        } else {
            return $rs;
        }
    }

    public function baseErrorMessage()
    {
        global $debug_mode;

        if ($this->DB->ErrorMsg() &&
          (DatabaseTypes::MSSQL != $this->DB_type || (!strstr($this->DB->ErrorMsg(), 'Changed database context to') &&
                                         !strstr($this->DB->ErrorMsg(), 'Changed language setting to')))) {
            return '</TABLE></TABLE></TABLE>' .
               '<FONT COLOR="#FF0000"><B>' . _ERRSQLDB . '</B>' . ($this->DB->ErrorMsg()) . '</FONT>' .
               '<P><CODE>' . ($debug_mode > 0 ? $this->lastSQL : '') . '</CODE><P>';
        }
    }

    public function baseTableExists($table)
    {
        if (DatabaseTypes::ORACLE == $this->DB_type) {
            $table = strtoupper($table);
        }

        if (in_array($table, $this->DB->MetaTables())) {
            return 1;
        } else {
            return 0;
        }
    }

    public function baseIndexExists($table, $index_name)
    {
        if (in_array($index_name, $this->DB->MetaIndexes($table))) {
            return 1;
        } else {
            return 0;
        }
    }

    public function baseInsertID()
    {
        /* Getting the insert ID fails on certain databases (e.g. postgres), but we may use it on the once it works
         * on.  This function returns -1 if the dbtype is postgres, then we can run a kludge query to get the insert
         * ID.  That query may vary depending upon which table you are looking at and what variables you have set at
         * the current point, so it can't be here and needs to be in the actual script after calling this function
         *  -- srh (02/01/2001)
         */
        if ((DatabaseTypes::MYSQL == $this->DB_type) || (DatabaseTypes::MSSQL == $this->DB_type)) {
            return $this->DB->Insert_ID();
        } elseif (DatabaseTypes::POSTGRES == $this->DB_type || (DatabaseTypes::ORACLE == $this->DB_type)) {
            return -1;
        }
    }

    public function baseTimestampFmt($timestamp)
    {
        // Not used anywhere????? -- Kevin
        return $this->DB->DBTimeStamp($timestamp);
    }

    public function baseSQL_YEAR($func_param, $op, $timestamp)
    {
        switch ($this->DB_type) {
            case DatabaseTypes::MYSQL:
            case DatabaseTypes::MSSQL:
                return " YEAR($func_param) $op $timestamp ";

            case DatabaseTypes::ORACLE:
                return " to_number( to_char( $func_param, 'RRRR' ) ) $op $timestamp ";

            case DatabaseTypes::POSTGRES:
                return " DATE_PART('year', $func_param) $op $timestamp ";

            default:
                throw new UnknownDatabaseException();
        }
    }

    public function baseSQL_MONTH($func_param, $op, $timestamp)
    {
        switch ($this->DB_type) {
            case DatabaseTypes::MYSQL:
            case DatabaseTypes::MSSQL:
                return " MONTH($func_param) $op $timestamp ";

            case DatabaseTypes::ORACLE:
                return " to_number( to_char( $func_param, 'MM' ) ) $op $timestamp ";

            case DatabaseTypes::POSTGRES:
                return " DATE_PART('month', $func_param) $op $timestamp ";

            default:
                throw new UnknownDatabaseException();
        }
    }

    public function baseSQL_DAY($func_param, $op, $timestamp): string
    {
        switch ($this->DB_type) {
            case DatabaseTypes::MYSQL:
                return " DAYOFMONTH($func_param) $op $timestamp ";

            case DatabaseTypes::ORACLE:
                return " to_number( to_char( $func_param, 'DD' ) ) $op $timestamp ";

            case DatabaseTypes::POSTGRES:
                return " DATE_PART('day', $func_param) $op $timestamp ";

            case DatabaseTypes::MSSQL:
                return " DAY($func_param) $op $timestamp ";

            default:
                throw new UnknownDatabaseException();
        }
    }

    public function baseSQL_HOUR($func_param, $op, $timestamp)
    {
        if ((DatabaseTypes::MYSQL == $this->DB_type)) {
            return " HOUR($func_param) $op $timestamp ";
        } elseif (DatabaseTypes::ORACLE == $this->DB_type) {
            return " to_number( to_char( $func_param, 'HH' ) ) $op $timestamp ";
        } elseif (DatabaseTypes::POSTGRES == $this->DB_type) {
            return " DATE_PART('hour', $func_param) $op $timestamp ";
        } elseif (DatabaseTypes::MSSQL == $this->DB_type) {
            return " DATEPART(hh, $func_param) $op $timestamp ";
        }
    }

    public function baseSQL_MINUTE($func_param, $op, $timestamp)
    {
        if ((DatabaseTypes::MYSQL == $this->DB_type)) {
            return " MINUTE($func_param) $op $timestamp ";
        } elseif (DatabaseTypes::ORACLE == $this->DB_type) {
            return " to_number( to_char( $func_param, 'MI' ) ) $op $timestamp ";
        } elseif (DatabaseTypes::POSTGRES == $this->DB_type) {
            return " DATE_PART('minute', $func_param) $op $timestamp ";
        } elseif (DatabaseTypes::MSSQL == $this->DB_type) {
            return " DATEPART(mi, $func_param) $op $timestamp ";
        }
    }

    public function baseSQL_SECOND($func_param, $op, $timestamp)
    {
        if ((DatabaseTypes::MYSQL == $this->DB_type)) {
            return " SECOND($func_param) $op $timestamp ";
        } elseif (DatabaseTypes::ORACLE == $this->DB_type) {
            return " to_number( to_char( $func_param, 'SS' ) ) $op $timestamp ";
        } elseif (DatabaseTypes::POSTGRES == $this->DB_type) {
            return " DATE_PART('second', $func_param) $op $timestamp ";
        } elseif (DatabaseTypes::MSSQL == $this->DB_type) {
            return " DATEPART(ss, $func_param) $op $timestamp ";
        }
    }

    public function baseSQL_UNIXTIME($func_param, $op, $timestamp)
    {
        if ((DatabaseTypes::MYSQL == $this->DB_type)) {
            return " UNIX_TIMESTAMP($func_param) $op $timestamp ";
        } elseif (DatabaseTypes::ORACLE == $this->DB_type) {
            return " to_number( $func_param ) $op $timestamp ";
        } elseif (DatabaseTypes::POSTGRES == $this->DB_type) {
            if (('' == $op) && ('' == $timestamp)) {
                /* Catches the case where I want to get the UNIXTIME of a constant
                 *   i.e. DATE_PART('epoch', timestamp) > = DATE_PART('epoch', timestamp '20010124')
                 *                                            (This one /\ )
                 */
                return " DATE_PART('epoch', $func_param::timestamp) ";
            } else {
                return " DATE_PART('epoch', $func_param::timestamp) $op $timestamp ";
            }
        } elseif (DatabaseTypes::MSSQL == $this->DB_type) {
            return " DATEDIFF(ss, '1970-1-1 00:00:00', $func_param) $op $timestamp ";
        }
    }

    public function baseSQL_TIMESEC($func_param, $op, $timestamp)
    {
        if ((DatabaseTypes::MYSQL == $this->DB_type)) {
            return " TIME_TO_SEC($func_param) $op $timestamp ";
        } elseif (DatabaseTypes::ORACLE == $this->DB_type) {
            return " to_number( $func_param ) $op $timestamp ";
        } elseif (DatabaseTypes::POSTGRES == $this->DB_type) {
            if (('' == $op) && ('' == $timestamp)) {
                return " DATE_PART('second', DATE_PART('day', '$func_param') ";
            } else {
                return " DATE_PART('second', DATE_PART('day', $func_param) ) $op $timestamp ";
            }
        } elseif (DatabaseTypes::MSSQL == $this->DB_type) {
            if (('' == $op) && ('' == $timestamp)) {
                return " DATEPART(ss, DATEPART(dd, $func_parm) ";
            } else {
                return " DATEPART(ss, DATE_PART(dd, $func_param) ) $op $timestamp ";
            }
        }
    }

    public function baseGetDBversion()
    {
        return $this->version;
    }

    public function getSafeSQLString($str)
    {
        $t = str_replace('\\', '\\\\', $str);
        if (DatabaseTypes::MSSQL != $this->DB_type && DatabaseTypes::ORACLE != $this->DB_type) {
            $t = str_replace("'", "\'", $t);
        } else {
            $t = str_replace("'", "''", $t);
        }
        $t = str_replace('"', '\\\\"', $t);

        return $t;
    }
}
