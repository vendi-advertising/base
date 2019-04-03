<?php

namespace Vendi\BASE\Criteria;

class UDPFieldCriteria extends ProtocolFieldCriteria
{
/*
 * $udp_field[MAX][6]: stores all other udp fields parameters/operators row
 *  - [][0] : (                            [][3] : field value
 *  - [][1] : length                       [][4] : (, )
 *  - [][2] : =, !=, <, <=, >, >=          [][5] : AND, OR
 *
 * $udp_field_cnt: number of rows in the $udp_field[][] structure
 */

   function UDPFieldCriteria(&$db, &$cs, $export_name, $element_cnt)
   {
	$tdb =& $db;
	$cs =& $cs;

      parent::ProtocolFieldCriteria($tdb, $cs, $export_name, $element_cnt,
                                    array ("udp_len" => "length",
                                           "udp_csum" => "chksum"));
   }

   function PrintForm()
   {
      parent::PrintForm($this->valid_field_list, _DISPFIELD, _ADDUDPFIELD);
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