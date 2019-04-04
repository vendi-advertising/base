<?php

namespace Vendi\BASE\Criteria;

class AlertGroupCriteria extends SingleElementCriteria
{
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
      $this->criteria = CleanVariable($this->criteria, VAR_DIGIT);
   }

   function PrintForm($field_list, $blank_field_string, $add_button_string)
   {

      echo '<SELECT NAME="ag">
             <OPTION VALUE=" " '.chk_select($this->criteria, " ").'>'._DISPANYAG;

      $temp_sql = "SELECT ag_id, ag_name FROM acid_ag";
      $tmp_result = $this->db->baseExecute($temp_sql);
      if ( $tmp_result )
      {
         while ( $myrow = $tmp_result->baseFetchRow() )
           echo '<OPTION VALUE="'.$myrow[0].'" '.chk_select($this->criteria, $myrow[0]).'>'.
                 '['.$myrow[0].'] '.htmlspecialchars($myrow[1]);

         $tmp_result->baseFreeRows();
      }
      echo '</SELECT>&nbsp;&nbsp;';
   }

   function ToSQL()
   {
    /* convert this criteria to SQL */
   }

   function Description($human_fields = null)
   {
      $tmp = "";

      if ( $this->criteria != " " && $this->criteria != "" )
        $tmp = $tmp._ALERTGROUP.' = ['.htmlentities($this->criteria).'] '.GetAGNameByID($this->criteria, $this->db).
                    $this->cs->GetClearCriteriaString($this->export_name).'<BR>';

      return $tmp;
   }
}
