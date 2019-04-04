<?php

namespace Vendi\BASE\Criteria;

class ProtocolFieldCriteria extends MultipleElementCriteria
{
	function __construct(&$db, &$cs, $export_name, $element_cnt, $field_list = Array() )
	{
		$tdb =& $db;
		$cs =& $cs;

		parent::__construct($tdb, $cs, $export_name, $element_cnt, $field_list);

	}



   function SanitizeElement($i = null)
   {
      // Make a copy of the element array
      $curArr = $this->criteria[$i];
      // Sanitize the element
      $this->criteria[$i][0] = @CleanVariable($curArr[0], VAR_OPAREN);
      $this->criteria[$i][1] = @CleanVariable($curArr[1], "", array_keys($this->valid_field_list));
      $this->criteria[$i][2] = @CleanVariable($curArr[2], "", array("=", "!=", "<", "<=", ">", ">="));
      $this->criteria[$i][3] = @CleanVariable($curArr[3], VAR_DIGIT);
      $this->criteria[$i][4] = @CleanVariable($curArr[4], VAR_OPAREN | VAR_CPAREN);
      $this->criteria[$i][5] = @CleanVariable($curArr[5], "", array("AND", "OR"));
      // Destroy the copy
      unset($curArr);
   }

   function Description($human_fields = null)
   {
      $tmp = "";
      for ( $i = 0; $i < $this->criteria_cnt; $i++ )
      {
	  if (is_array($this->criteria[$i]))
	      if ($this->criteria[$i][1] != " " && $this->criteria[$i][3] != "" )
		  $tmp = $tmp.$this->criteria[$i][0].$human_fields[($this->criteria[$i][1])].' '.
		      $this->criteria[$i][2].' '.$this->criteria[$i][3].$this->criteria[$i][4].' '.$this->criteria[$i][5];
      }
      if ( $tmp != "" )
         $tmp = $tmp.$this->cs->GetClearCriteriaString($this->export_name);

      return $tmp;
   }

   function ToSQL()
   {
      //NOOP
   }

   function Clear()
   {
      //NOOP
   }
}
