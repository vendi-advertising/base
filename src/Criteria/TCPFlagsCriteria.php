<?php

namespace Vendi\BASE\Criteria;

class TCPFlagsCriteria extends SingleElementCriteria
{
/*
 * $tcp_flags[7]: stores all other tcp flags parameters/operators row
 *  - [0] : is, contains                   [4] : 8     (RST)
 *  - [1] : 1   (FIN)                      [5] : 16    (ACK)
 *  - [2] : 2   (SYN)                      [6] : 32    (URG)
 *  - [3] : 4   (PUSH)
 */

   function Init()
   {
      InitArray($this->criteria, $GLOBALS['MAX_ROWS'], TCPFLAGS_CFCNT, "");
   }

   function Clear()
   {
     /* clears the criteria */
   }

   function SanitizeElement()
   {
      $this->criteria = CleanVariable($this->criteria, VAR_DIGIT);
   }

   function PrintForm()
   {
       		if (!is_array($this->criteria[0]))
			$this->criteria = array();

      echo '<TD><SELECT NAME="tcp_flags[0]"><OPTION VALUE=" " '.chk_select($this->criteria[0]," ").'>'._DISPFLAGS;
      echo '                              <OPTION VALUE="is" '.chk_select($this->criteria[0],"is").'>'._IS;
      echo '                              <OPTION VALUE="contains" '.chk_select($this->criteria[0],"contains").'>'._CONTAINS.'</SELECT>';
      echo '   <FONT>';
      echo '    <INPUT TYPE="checkbox" NAME="tcp_flags[8]" VALUE="128" '.chk_check($this->criteria[8],"128").'> [RSV1] &nbsp';
      echo '    <INPUT TYPE="checkbox" NAME="tcp_flags[7]" VALUE="64"  '.chk_check($this->criteria[7],"64").'> [RSV0] &nbsp';
      echo '    <INPUT TYPE="checkbox" NAME="tcp_flags[6]" VALUE="32"  '.chk_check($this->criteria[6],"32").'> [URG] &nbsp';
      echo '    <INPUT TYPE="checkbox" NAME="tcp_flags[5]" VALUE="16"  '.chk_check($this->criteria[5],"16").'> [ACK] &nbsp';
      echo '    <INPUT TYPE="checkbox" NAME="tcp_flags[3]" VALUE="8"   '.chk_check($this->criteria[4],"8").'> [PSH] &nbsp';
      echo '    <INPUT TYPE="checkbox" NAME="tcp_flags[4]" VALUE="4"   '.chk_check($this->criteria[3],"4").'> [RST] &nbsp';
      echo '    <INPUT TYPE="checkbox" NAME="tcp_flags[2]" VALUE="2"   '.chk_check($this->criteria[2],"2").'> [SYN] &nbsp';
      echo '    <INPUT TYPE="checkbox" NAME="tcp_flags[1]" VALUE="1"   '.chk_check($this->criteria[1],"1").'> [FIN] &nbsp';
      echo '  </FONT>';
   }

   function ToSQL()
   {
     /* convert this criteria to SQL */
   }

   function Description()
   {
      $human_fields["1"] = "F";
      $human_fields["2"] = "S";
      $human_fields["4"] = "R";
      $human_fields["8"] = "P";
      $human_fields["16"] = "A";
      $human_fields["32"] = "U";
      $human_fields["64"] = "[R0]";
      $human_fields["128"] = "[R1]";
      $human_fields["LIKE"] = _CONTAINS;
      $human_fields["="] = "=";

      $tmp = "";

      if ( isset($this->criteria[0]) && ($this->criteria[0] != " ") && ($this->criteria[0] != "") )
      {
         $tmp = $tmp.'flags '.$this->criteria[0].' ';
         for ( $i = 8; $i >=1; $i-- )
            if ( $this->criteria[$i] == "" )
               $tmp = $tmp.'-';
            else
               $tmp = $tmp.$human_fields[($this->criteria[$i])];

         $tmp = $tmp.$this->cs->GetClearCriteriaString("tcp_flags").'<BR>';
      }

      return $tmp;
   }

   function isEmpty()
   {
     if ( strlen($this->criteria) != 0 && ($this->criteria[0] != "") && ($this->criteria[0] != " ") )
        return false;
     else
        return true;
   }
}
