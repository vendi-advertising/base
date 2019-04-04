<?php

namespace Vendi\BASE\Criteria;

class SignatureCriteria extends SingleElementCriteria
{
/*
 * $sig[4]: stores signature
 *   - [0] : exactly, roughly
 *   - [1] : signature
 *   - [2] : =, !=
 *   - [3] : signature from signature list
 */

   var $sig_type;
   var $criteria = array(0 => '', 1 => '');

   function __construct(&$db, &$cs, $export_name)
   {
	$tdb =& $db;
	$cs =& $cs;

      parent::__construct($tdb, $cs, $export_name);

      $this->sig_type = "";
   }

   function Init()
   {
      InitArray($this->criteria, 4, 0, "");
      $this->sig_type = "";
   }

   function Import()
   {
      parent::Import();

      $this->sig_type = SetSessionVar("sig_type");

      $_SESSION['sig_type'] = &$this->sig_type;
   }

   function Clear()
   {
   }

   function SanitizeElement($i = null)
   {
      if (!isset($this->criteria[0]) || !isset($this->criteria[1])) {
          $this->criteria = array(0 => '', 1 => '');
      }

      $this->criteria[0] = CleanVariable(@$this->criteria[0], "", array(" ", "=", "LIKE"));
      $this->criteria[1] = filterSql(@$this->criteria[1]); /* signature name */
      $this->criteria[2] = CleanVariable(@$this->criteria[2], "", array("=", "!="));
      $this->criteria[3] = filterSql(@$this->criteria[3]); /* signature name from the signature list */
   }

   function PrintForm($field_list, $blank_field_string, $add_button_string)
   {
      if (!@is_array($this->criteria))
        $this->criteria = array();

      echo '<SELECT NAME="sig[0]"><OPTION VALUE=" "  '.chk_select(@$this->criteria[0]," "). '>'._DISPSIG;
      echo '                      <OPTION VALUE="="     '.chk_select(@$this->criteria[0],"="). '>'._SIGEXACTLY;
      echo '                      <OPTION VALUE="LIKE" '.chk_select(@$this->criteria[0],"LIKE").'>'._SIGROUGHLY.'</SELECT>';

      echo '<SELECT NAME="sig[2]"><OPTION VALUE="="  '.chk_select(@$this->criteria[2],"="). '>=';
      echo '                      <OPTION VALUE="!="     '.chk_select(@$this->criteria[2],"!="). '>!=';
      echo '</SELECT>';

      echo '<INPUT TYPE="text" NAME="sig[1]" SIZE=40 VALUE="'.htmlspecialchars(@$this->criteria[1]).'"><BR>';

      if ( $GLOBALS['use_sig_list'] > 0)
      {
         $temp_sql = "SELECT DISTINCT sig_name FROM signature";
         if ($GLOBALS['use_sig_list'] == 1)
         {
            $temp_sql = $temp_sql." WHERE sig_name NOT LIKE '%SPP\_%'";
         }

         $temp_sql = $temp_sql." ORDER BY sig_name";
         $tmp_result = $this->db->baseExecute($temp_sql);
         echo '<SELECT NAME="sig[3]"
                       onChange=\'PacketForm.elements[4].value =
                         this.options[this.selectedIndex].value;return true;\'>
                <OPTION VALUE="null" SELECTED>{ Select Signature from List }';

         if ($tmp_result)
         {
            while ( $myrow = $tmp_result->baseFetchRow() )
               echo '<OPTION VALUE="'.$myrow[0].'">'.$myrow[0];
            $tmp_result->baseFreeRows();
         }
         echo '</SELECT><BR>';
      }

   }

   function ToSQL()
   {
   }

   function Description($human_fields = null)
   {
      $tmp = $tmp_human = "";


      // First alternative: signature name is taken from the
      // signature list.  The user has clicked at a drop down menu for this
      if (
           (isset($this->criteria[0])) && ($this->criteria[0] != " ") &&
           (isset($this->criteria[3])) && ($this->criteria[3] != "" ) &&
           ($this->criteria[3] != "null") && ($this->criteria[3] != "NULL") &&
           ($this->criteria[3] != NULL)
         )
      {
        if ( $this->criteria[0] == '=' && $this->criteria[2] == '!=' )
           $tmp_human = '!=';
        else if ( $this->criteria[0] == '=' && $this->criteria[2] == '=' )
           $tmp_human = '=';
        else if ( $this->criteria[0] == 'LIKE' && $this->criteria[2] == '!=' )
           $tmp_human = ' '._DOESNTCONTAIN.' ';
        else if ( $this->criteria[0] == 'LIKE' && $this->criteria[2] == '=' )
           $tmp_human = ' '._CONTAINS.' ';

        $tmp = $tmp._SIGNATURE.' '.$tmp_human.' "';
        if ( ($this->db->baseGetDBversion() >= 100) && $this->sig_type == 1 )
          $tmp = $tmp.BuildSigByID($this->criteria[3], $this->db).'" '.$this->cs->GetClearCriteriaString($this->export_name);
        else
          $tmp = $tmp.htmlentities($this->criteria[3]).'"'.$this->cs->GetClearCriteriaString($this->export_name);

        $tmp = $tmp.'<BR>';
      }
      else
      // Second alternative: Signature is taken from a string that
      // has been typed in manually by the user:
      if ( (isset($this->criteria[0])) && ($this->criteria[0] != " ") &&
           (isset($this->criteria[1])) && ($this->criteria[1] != "") )
      {
        if ( $this->criteria[0] == '=' && $this->criteria[2] == '!=' )
           $tmp_human = '!=';
        else if ( $this->criteria[0] == '=' && $this->criteria[2] == '=' )
           $tmp_human = '=';
        else if ( $this->criteria[0] == 'LIKE' && $this->criteria[2] == '!=' )
           $tmp_human = ' '._DOESNTCONTAIN.' ';
        else if ( $this->criteria[0] == 'LIKE' && $this->criteria[2] == '=' )
           $tmp_human = ' '._CONTAINS.' ';

        $tmp = $tmp._SIGNATURE.' '.$tmp_human.' "';
        if ( ($this->db->baseGetDBversion() >= 100) && $this->sig_type == 1 )
          $tmp = $tmp.BuildSigByID($this->criteria[1], $this->db).'" '.$this->cs->GetClearCriteriaString($this->export_name);
        else
          $tmp = $tmp.htmlentities($this->criteria[1]).'"'.$this->cs->GetClearCriteriaString($this->export_name);

        $tmp = $tmp.'<BR>';
      }

      return $tmp;
   }
}
