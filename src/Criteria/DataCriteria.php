<?php

namespace Vendi\BASE\Criteria;

class DataCriteria extends MultipleElementCriteria
{
/*
 * $data_encode[2]: how the payload should be interpreted and converted
 *  - [0] : encoding type (hex, ascii)
 *  - [1] : conversion type (hex, ascii)
 *
 * $data[MAX][5]: stores all the payload related parameters/operators row
 *  - [][0] : (                            [][3] : (, )
 *  - [][1] : =, !=                        [][4] : AND, OR
 *  - [][2] : field value
 *
 * $data_cnt: number of rows in the $data[][] structure
 */

   var $data_encode;

   function DataCriteria(&$db, &$cs, $export_name, $element_cnt)
   {
	$tdb =& $db;
	$cs =& $cs;

      parent::MultipleElementCriteria($tdb, $cs, $export_name, $element_cnt,
                                      array ("LIKE" => _HAS,
                                             "NOT LIKE" => _HASNOT ));
      $this->data_encode = array();
   }

   function Init()
   {
      parent::Init();
      InitArray($this->data_encode, 2, 0, "");
   }

   function Import()
   {
      parent::Import();

      $this->data_encode = SetSessionVar("data_encode");

      $_SESSION['data_encode'] = &$this->data_encode;
  }

   function Clear()
   {
     /* clears the criteria */
   }

   function SanitizeElement($i)
   {
      $this->data_encode[0] = CleanVariable($this->data_encode[0], "", array("hex", "ascii"));
      $this->data_encode[1] = CleanVariable($this->data_encode[1], "", array("hex", "ascii"));
      // Make a copy of the element array
      $curArr = $this->criteria[$i];
      // Sanitize the array
      $this->criteria[$i][0] = CleanVariable($curArr[0], VAR_OPAREN);
      $this->criteria[$i][1] = CleanVariable($curArr[1], "", array_keys($this->valid_field_list));
      $this->criteria[$i][2] = CleanVariable($curArr[2], VAR_FSLASH | VAR_PERIOD | VAR_DIGIT | VAR_PUNC | VAR_LETTER );
      $this->criteria[$i][3] = CleanVariable($curArr[3], VAR_OPAREN | VAR_CPAREN);
      $this->criteria[$i][4] = CleanVariable($curArr[4], "", array("AND", "OR"));
      // Destroy the copy
      unset($curArr);
   }

   function PrintForm()
   {
	            if (!is_array(@$this->criteria[0]))
			$this->criteria = array();

      echo '<B>'._INPUTCRTENC.':</B>';
      echo '<SELECT NAME="data_encode[0]"><OPTION VALUE=" "    '.@chk_select($this->data_encode[0]," ").'>'._DISPENCODING;
      echo '                              <OPTION VALUE="hex"  '.@chk_select($this->data_encode[0],"hex").'>hex';
      echo '                              <OPTION VALUE="ascii"'.@chk_select($this->data_encode[0],"ascii").'>ascii</SELECT>';
      echo '<B>'._CONVERT2WS.':</B>';
      echo '<SELECT NAME="data_encode[1]"><OPTION VALUE=" "    '.@chk_select(@$this->data_encode[1]," ").'>'._DISPCONVERT2;
      echo '                              <OPTION VALUE="hex"  '.@chk_select(@$this->data_encode[1],"hex").'>hex';
      echo '                              <OPTION VALUE="ascii"'.@chk_select(@$this->data_encode[1],"ascii").'>ascii</SELECT>';
      echo '<BR>';

      for ( $i = 0; $i < $this->criteria_cnt; $i++ )
      {
         echo '<SELECT NAME="data['.$i.'][0]"><OPTION VALUE=" " '.chk_select(@$this->criteria[$i][0]," ").'>__';
         echo '                               <OPTION VALUE="("  '.chk_select(@$this->criteria[$i][0],"(").'>(</SELECT>';
         echo '<SELECT NAME="data['.$i.'][1]"><OPTION VALUE=" "  '.chk_select(@$this->criteria[$i][1]," "). '>'._DISPPAYLOAD;
         echo '                               <OPTION VALUE="LIKE"     '.chk_select(@$this->criteria[$i][1],"LIKE"). '>'._HAS;
         echo '                               <OPTION VALUE="NOT LIKE" '.chk_select(@$this->criteria[$i][1],"NOT LIKE").'>'._HASNOT.'</SELECT>';

         echo '<INPUT TYPE="text" NAME="data['.$i.'][2]" SIZE=45 VALUE="'.htmlspecialchars(@$this->criteria[$i][2]).'">';

         echo '<SELECT NAME="data['.$i.'][3]"><OPTION VALUE=" " '.chk_select(@$this->criteria[$i][3]," ").'>__';
         echo '                               <OPTION VALUE="(" '.chk_select(@$this->criteria[$i][3],"(").'>(';
         echo '                               <OPTION VALUE=")" '.chk_select(@$this->criteria[$i][3],")").'>)</SELECT>';
         echo '<SELECT NAME="data['.$i.'][4]"><OPTION VALUE=" "   '.chk_select(@$this->criteria[$i][4]," ").  '>__';
         echo '                               <OPTION VALUE="OR" '.chk_select(@$this->criteria[$i][4],"OR").  '>'._OR;
         echo '                               <OPTION VALUE="AND" '.chk_select(@$this->criteria[$i][4],"AND").'>'._AND.'</SELECT>';

         if ( $i == $this->criteria_cnt-1 )
            echo '    <INPUT TYPE="submit" NAME="submit" VALUE="'._ADDPAYLOAD.'">';
         echo '<BR>';
      }
   }

   function ToSQL()
   {
     /* convert this criteria to SQL */
   }

   function Description()
   {
      $human_fields["LIKE"] = _CONTAINS;
      $human_fields["NOT LIKE"] = _DOESNTCONTAIN;
      $human_fields[""] = "";

      $tmp = "";

      if ( $this->data_encode[0] != " " && $this->data_encode[1] != " ")
      {
          $tmp = $tmp.' ('._DENCODED.' '.$this->data_encode[0];
          $tmp = $tmp.' => '.$this->data_encode[1];
          $tmp = $tmp.')<BR>';
      }
      else
          $tmp = $tmp.' '._NODENCODED.'<BR>';

      for ( $i = 0; $i < $this->criteria_cnt; $i++ )
      {
         if ($this->criteria[$i][1] != " " && $this->criteria[$i][2] != "" )
            $tmp = $tmp.$this->criteria[$i][0].$human_fields[$this->criteria[$i][1]].' "'.$this->criteria[$i][2].
                             '" '.$this->criteria[$i][3].' '.$this->criteria[$i][4];
      }

      if ( $tmp != "" )
         $tmp = $tmp.$this->cs->GetClearCriteriaString($this->export_name);

      return $tmp;
   }
}
