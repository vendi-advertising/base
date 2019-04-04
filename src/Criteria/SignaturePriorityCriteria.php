<?php

namespace Vendi\BASE\Criteria;

class SignaturePriorityCriteria extends SingleElementCriteria
{
   var $criteria = array();
   function Init()
   {
     $this->criteria = "";
   }

   function Clear()
   {
    /* clears the criteria */
   }

   function SanitizeElement($i = null)
   {
     if (!isset($this->criteria[0]) || !isset($this->criteria[1])) {
         $this->criteria = array(0 => '', 1 => '');
     }

      $this->criteria[0] = CleanVariable(@$this->criteria[0], "", array("=", "!=", "<", "<=", ">", ">="));
      $this->criteria[1] = CleanVariable(@$this->criteria[1], VAR_DIGIT);
   }

   function PrintForm($field_list, $blank_field_string, $add_button_string)
   {
     if ( $this->db->baseGetDBversion() >= 103 )
     {
  		if (!@is_array($this->criteria))
			$this->criteria = array();

        echo '<SELECT NAME="sig_priority[0]">
                <OPTION VALUE=" " '.@chk_select($this->criteria[0],"="). '>__</OPTION>
                <OPTION VALUE="=" '.@chk_select($this->criteria[0],"=").'>==</OPTION>
                <OPTION VALUE="!=" '.@chk_select($this->criteria[0],"!=").'>!=</OPTION>
                <OPTION VALUE="<"  '.@chk_select($this->criteria[0],"<"). '><</OPTION>
                <OPTION VALUE=">"  '.@chk_select($this->criteria[0],">").'>></OPTION>
                <OPTION VALUE="<=" '.@chk_select($this->criteria[0],"><="). '><=</OPTION>
                <OPTION VALUE=">=" '.@chk_select($this->criteria[0],">=").'>>=</SELECT>';

        echo '<SELECT NAME="sig_priority[1]">
                <OPTION VALUE="" '.@chk_select($this->criteria[1], " ").'>'._DISPANYPRIO.'</OPTION>
 	        <OPTION VALUE="null" '.@chk_select($this->criteria[1], "null").'>-'._UNCLASS.'-</OPTION>';
        $temp_sql = "select DISTINCT sig_priority from signature ORDER BY sig_priority ASC ";
        $tmp_result = $this->db->baseExecute($temp_sql);
        if ( $tmp_result )
        {
           while ( $myrow = $tmp_result->baseFetchRow() )
             echo '<OPTION VALUE="'.$myrow[0].'" '.chk_select(@$this->criteria[1], $myrow[0]).'>'.
                   $myrow[0];

            $tmp_result->baseFreeRows();
        }
        echo '</SELECT>&nbsp;&nbsp';
      }
    }

    function ToSQL()
    {
    /* convert this criteria to SQL */
    }

    function Description($human_fields)
    {
       $tmp = "";
       if (!isset($this->criteria[1])) {
           $this->criteria = array(0 => '', 1 => '');
       }

       if ( $this->db->baseGetDBversion() >= 103 )
       {
          if ( $this->criteria[1] != " " && $this->criteria[1] != "" )
          {
             if ( $this->criteria[1] == null)
                $tmp = $tmp._SIGPRIO.' = '.
                               '<I>'._NONE.'</I><BR>';
             else
                $tmp = $tmp._SIGPRIO.' '.htmlentities($this->criteria[0])." ".htmlentities($this->criteria[1]).
                       $this->cs->GetClearCriteriaString($this->export_name).'<BR>';
          }
       }

       return $tmp;
    }
 }
