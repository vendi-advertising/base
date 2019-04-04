<?php

use Vendi\BASE\baseCon;
use Vendi\BASE\DatabaseTypes;

require_once dirname(__DIR__) . '/includes/vendi_boot.php';

defined( '_BASE_INC' ) or die( 'Accessing this file directly is not allowed.' );


function VerifyDBAbstractionLib($path)
{
  GLOBAL $debug_mode;

  if ( $debug_mode > 0 )
      echo(_DBALCHECK." '$path'<BR>");

  if( !ini_get('safe_mode') ){
    if ( is_readable($path) ) // is_file
        return true;
     else
     {
        echo _ERRSQLDBALLOAD1.'"'.$path.
             '"'._ERRSQLDBALLOAD2;

        die();
     }
  }
}

function NewBASEDBConnection($path, $type)
{
  GLOBAL $debug_mode;
  if(!in_array($type, DatabaseTypes::get_support_database_types())) {
     echo "<B>"._ERRSQLDBTYPE."</B>".
            "<P>:"._ERRSQLDBTYPEINFO1."<CODE>'$type'</CODE>. "._ERRSQLDBTYPEINFO2;
     die();
  }

   /* Export ADODB_DIR for use by ADODB */
   /** Sometimes it may already be defined. So check to see if it is first -- Tim Rupp**/
   if (!defined('ADODB_DIR')) {
   	define('ADODB_DIR', $path);
   }
   	$GLOBALS['ADODB_DIR'] = $path;

   $last_char =  substr($path, strlen($path)-1, 1);

   if ( $debug_mode > 1 )
      echo "Original path = '".$path."'<BR>";

   if ( $last_char == "\\" || $last_char == "/" )
   {
      if ( $debug_mode > 1 ) echo "Attempting to load: '".$path."adodb.inc.php'<BR>";

      VerifyDBAbstractionLib($path."adodb.inc.php");
      include($path."adodb.inc.php");
   }
   else if ( strstr($path,"/") || $path == "" )
   {
      if ( $debug_mode > 1 ) echo "Attempting to load: '".$path."/adodb.inc.php'<BR>";

      VerifyDBAbstractionLib($path."/adodb.inc.php");
      include($path."/adodb.inc.php");
   }
   else if ( strstr($path,"\\") )
   {
      if ( $debug_mode > 1 ) echo "Attempting to load: '".$path."\\adodb.inc.php'<BR>";

      VerifyDBAbstractionLib($path."\\adodb.inc.php");
      include($path."\\adodb.inc.php");
   }

   ADOLoadCode($type);

   return new baseCon($type);
}

function MssqlKludgeValue($text)
{
   $mssql_kludge = "";
   for ($i = 0 ; $i < strlen($text) ; $i++)
   {
      $mssql_kludge = $mssql_kludge."[".
                      substr($text,$i, 1)."]";
   }
   return $mssql_kludge;
}

function RepairDBTables($db)
{
  /* This function was completely commented in original....
    I will be searching to see where it was called from if at all */
}

function ClearDataTables($db)
{
  $db->baseExecute("DELETE FROM acid_event");
  $db->baseExecute("DELETE FROM data");
  $db->baseExecute("DELETE FROM event");
  $db->baseExecute("DELETE FROM icmphdr");
  $db->baseExecute("DELETE FROM iphdr");
  $db->baseExecute("DELETE FROM reference");
  $db->baseExecute("DELETE FROM sensor");
  $db->baseExecute("DELETE FROM sig_class");
  $db->baseExecute("DELETE FROM sig_reference");
  $db->baseExecute("DELETE FROM signature");
  $db->baseExecute("DELETE FROM tcphdr");
  $db->baseExecute("DELETE FROM udphdr");
}
// vim:tabstop=2:shiftwidth=2:expandtab
