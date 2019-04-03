<?php

namespace Vendi\BASE\Criteria;

class BaseCriteria
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

   function Init()
   {
   }

   function Import()
   {
     /* imports criteria from POST, GET, or the session */
   }

   function Clear()
   {
     /* clears the criteria */
   }

   function Sanitize()
   {
     /* clean/validate the criteria */
   }

   function SanitizeElement()
   {
     /* clean/validate the criteria */
   }

   function PrintForm()
   {
     /* prints the HTML form to input the criteria */
   }

   function AddFormItem()
   {
     /* adding another item to the HTML form  */
   }

   function GetFormItemCnt()
   {
     /* returns the number of items in this form element  */
   }

   function SetFormItemCnt()
   {
     /* sets the number of items in this form element */
   }

   function Set($value)
   {
     /* set the value of this criteria */
   }

   function Get()
   {
     /* returns the value of this criteria */
   }

   function ToSQL()
   {
     /* convert this criteria to SQL */
   }

   function Description()
   {
     /* generate human-readable description of this criteria */
   }

   function isEmpty()
   {
     /* returns if the criteria is empty */
   }
}
