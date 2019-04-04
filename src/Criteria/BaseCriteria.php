<?php

namespace Vendi\BASE\Criteria;

abstract class BaseCriteria
{
   var $criteria;
   var $export_name;

   var $db;
   var $cs;

   function __construct(&$db, &$cs, $name)
   {
     $this->db =& $db;
     $this->cs =& $cs;

     $this->export_name = $name;
     $this->criteria = NULL;
   }

      abstract function Init();

      /* imports criteria from POST, GET, or the session */
      abstract function Import();

      /* clears the criteria */
      abstract function Clear();

      /* clean/validate the criteria */
      abstract function Sanitize();

      /* clean/validate the criteria */
      abstract function SanitizeElement($i = null);

      /* prints the HTML form to input the criteria */
      abstract function PrintForm($field_list, $blank_field_string, $add_button_string);

      /* returns the number of items in this form element  */
      abstract function GetFormItemCnt();

      /* sets the number of items in this form element */
      abstract function SetFormItemCnt($value);

      /* set the value of this criteria */
      abstract function Set($value);

      /* returns the value of this criteria */
      abstract function Get();

      /* convert this criteria to SQL */
      abstract function ToSQL();

      /* generate human-readable description of this criteria */
      abstract function Description($human_fields);

      /* returns if the criteria is empty */
      abstract function isEmpty();
}
