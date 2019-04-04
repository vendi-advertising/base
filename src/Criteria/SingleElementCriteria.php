<?php

namespace Vendi\BASE\Criteria;

abstract class SingleElementCriteria extends BaseCriteria
{
   function Import()
   {
      $this->criteria = SetSessionVar($this->export_name);

      $_SESSION[$this->export_name] = &$this->criteria;
   }

   function Sanitize()
   {
      $this->SanitizeElement();
   }

   function GetFormItemCnt()
   {
      return -1;
   }

   function SetFormItemCnt($value)
   {
      //NOOP
   }

   function Set($value)
   {
      $this->criteria = $value;
   }

   function Get()
   {
      return $this->criteria;
   }
   function isEmpty()
   {
      if ( $this->criteria == "" )
         return true;
      else
         return false;
   }
};
