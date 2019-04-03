<?php

namespace Vendi\BASE\Criteria;

class ICMPFieldCriteria extends ProtocolFieldCriteria
{
/*
 * $icmp_field[MAX][6]: stores all other icmp fields parameters/operators row
 *  - [][0] : (                            [][3] : field value
 *  - [][1] : code, length                 [][4] : (, )
 *  - [][2] : =, !=, <, <=, >, >=          [][5] : AND, OR
 *
 * $icmp_field_cnt: number of rows in the $icmp_field[][] structure
 */

   function ICMPFieldCriteria(&$db, &$cs, $export_name, $element_cnt)
   {
	$tdb =& $db;
	$cs =& $cs;

      parent::ProtocolFieldCriteria($tdb, $cs, $export_name, $element_cnt,
                                    array ("icmp_type" => "type",
                                           "icmp_code" => "code",
                                           "icmp_id"   => "id",
                                           "icmp_seq"  => "seq #",
                                           "icmp_csum" => "chksum"));
   }

   function PrintForm()
   {
      parent::PrintForm($this->valid_field_list, _DISPFIELD, _ADDICMPFIELD);
   }

   function ToSQL()
   {
     /* convert this criteria to SQL */
   }

   function Description()
   {
      return parent::Description(array_merge ( array("" => ""), $this->valid_field_list) );
   }
}