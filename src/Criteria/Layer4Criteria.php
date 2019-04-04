<?php

namespace Vendi\BASE\Criteria;

class Layer4Criteria extends SingleElementCriteria
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
      $this->criteria = CleanVariable($this->criteria, "", array("UDP", "TCP", "ICMP", "RawIP"));
   }

   function PrintForm($field_list, $blank_field_string, $add_button_string)
   {
      if ( $this->criteria != "" )
         echo '<INPUT TYPE="submit" NAME="submit" VALUE="'._NOLAYER4.'"> &nbsp';
      if ( $this->criteria == "TCP" )
         echo '
           <INPUT TYPE="submit" NAME="submit" VALUE="UDP"> &nbsp
           <INPUT TYPE="submit" NAME="submit" VALUE="ICMP">';
      else if ( $this->criteria == "UDP" )
         echo '
           <INPUT TYPE="submit" NAME="submit" VALUE="TCP"> &nbsp
           <INPUT TYPE="submit" NAME="submit" VALUE="ICMP">';
      else if ( $this->criteria == "ICMP" )
         echo '
           <INPUT TYPE="submit" NAME="submit" VALUE="TCP"> &nbsp
           <INPUT TYPE="submit" NAME="submit" VALUE="UDP">';
      else
         echo '
           <INPUT TYPE="submit" NAME="submit" VALUE="TCP"> &nbsp
           <INPUT TYPE="submit" NAME="submit" VALUE="UDP">
           <INPUT TYPE="submit" NAME="submit" VALUE="ICMP">';
   }

   function ToSQL()
   {
     /* convert this criteria to SQL */
   }

   function Description($human_fields = null)
   {
      if ( $this->criteria == "TCP" )
         return _QCTCPCRIT;
      else if ( $this->criteria == "UDP" )
         return _QCUDPCRIT;
      else if ( $this->criteria == "ICMP" )
         return _QCICMPCRIT ;
      else
         return _QCLAYER4CRIT;
   }
}
