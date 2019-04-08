<?php

declare(strict_types=1);

namespace Vendi\BASE;

class baseRS
{
    public $row;
    public $DB_type;

    public function __construct($id, $type)
    {
        $this->row = $id;
        $this->DB_type = $type;
    }

    public function baseFetchRow()
    {
        global $debug_mode;

        /* Workaround for the problem, that the database may contain NULL
         * whereas "NOT NULL" has been defined, when it was created */
        if (!is_object($this->row)) {
            if ($debug_mode > 1) {
                echo '<BR><BR>' . __FILE__ . ':' . __LINE__ . ': ERROR: $this->row is not an object (1)<BR><PRE>';
                debug_print_backtrace();
                echo '<BR><BR>';
                echo 'var_dump($this):<BR>';
                var_dump($this);
                echo '<BR><BR>';
                echo 'var_dump($this->row):<BR>';
                var_dump($this->row);
                echo '</PRE><BR><BR>';
            }

            return '';
        }
        if (!$this->row->EOF) {
            $temp = $this->row->fields;
            $this->row->MoveNext();

            return $temp;
        } else {
            return '';
        }
    }

    public function baseColCount()
    {
        // Not called anywhere???? -- Kevin
        return $this->row->FieldCount();
    }

    public function baseRecordCount()
    {
        global $debug_mode;

        if (!is_object($this->row)) {
            if ($debug_mode > 1) {
                echo '<BR><BR>';
                echo __FILE__ . ':' . __LINE__ . ': ERROR: $this->row is not an object (2).';
                echo '<BR><PRE>';
                debug_print_backtrace();
                echo '<BR><BR>var_dump($this):<BR>';
                var_dump($this);
                echo '<BR><BR>var_dump($this->row):<BR>';
                var_dump($this->row);
                echo '</PRE><BR><BR>';
            }

            return 0;
        }

        // Is This if statement necessary?  -- Kevin
        /* MS SQL Server 7, MySQL, Sybase, and Postgres natively support this function */
        if (in_array($this->DB_type, DatabaseTypes::get_support_database_types())) {
            return $this->row->RecordCount();
        }

        /* Otherwise we need to emulate this functionality */
        $i = 0;
        while (!$this->row->EOF) {
            ++$i;
            $this->row->MoveNext();
        }

        return $i;
    }

    public function baseFreeRows()
    {
        global $debug_mode;

        /* Workaround for the problem, that the database may contain NULL,
         * although "NOT NULL" had been defined when it had been created.
         * In such a case there's nothing to free(). So we can ignore this
         * row and don't have anything to do. */
        if (!is_object($this->row)) {
            if ($debug_mode > 1) {
                echo '<BR><BR>';
                echo __FILE__ . ':' . __LINE__ . ': ERROR: $this->row is not an object (3).';
                echo '<BR><PRE>';
                debug_print_backtrace();
                echo '<BR><BR>var_dump($this):<BR>';
                var_dump($this);
                echo '<BR><BR>var_dump($this->row):<BR>';
                var_dump($this->row);
                echo '</PRE><BR><BR>';
            }
        } else {
            $this->row->Close();
        }
    }
}
