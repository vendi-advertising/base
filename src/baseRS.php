<?php

namespace Vendi\BASE;

class baseRS {

  var $row;
  var $DB_type;

  function __construct($id, $type)
  {
     $this->row = $id;
     $this->DB_type = $type;
  }

  function baseFetchRow()
  {
    GLOBAL $debug_mode;


     /* Workaround for the problem, that the database may contain NULL
      * whereas "NOT NULL" has been defined, when it was created */
     if (!is_object($this->row))
     {
       if ($debug_mode > 1)
       {
         echo "<BR><BR>" . __FILE__ . ':' . __LINE__ . ": ERROR: \$this->row is not an object (1)<BR><PRE>";
         debug_print_backtrace();
         echo "<BR><BR>";
         echo "var_dump(\$this):<BR>";
         var_dump($this);
         echo "<BR><BR>";
         echo "var_dump(\$this->row):<BR>";
         var_dump($this->row);
         echo "</PRE><BR><BR>";
       }

       return "";
     }
     if ( !$this->row->EOF )
     {
        $temp = $this->row->fields;
        $this->row->MoveNext();
        return $temp;
     }
     else
        return "";
  }

  function baseColCount()
  {
    // Not called anywhere???? -- Kevin
     return $this->row->FieldCount();
  }

  function baseRecordCount()
  {
    GLOBAL $debug_mode;

    if (!is_object($this->row))
    {
      if ($debug_mode > 1)
      {
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
     if ( ($this->DB_type == "mysql") || ($this->DB_type == "mysqlt") || ($this->DB_type == "maxsql") ||
          ($this->DB_type == "mssql") || ($this->DB_type == "sybase") || ($this->DB_type == "postgres") || ($this->DB_type == "oci8"))
        return $this->row->RecordCount();

     /* Otherwise we need to emulate this functionality */
     else
     {
          $i = 0;
          while ( !$this->row->EOF )
          {
             ++$i;
             $this->row->MoveNext();
          }

          return $i;
     }
  }

  function baseFreeRows()
  {
    GLOBAL $debug_mode;

    /* Workaround for the problem, that the database may contain NULL,
     * although "NOT NULL" had been defined when it had been created.
     * In such a case there's nothing to free(). So we can ignore this
     * row and don't have anything to do. */
    if (!is_object($this->row))
    {
      if ($debug_mode > 1)
      {
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
    }
    else
    {
      $this->row->Close();
    }
  }
}
