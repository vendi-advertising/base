<?php

namespace Vendi\BASE\Criteria;

class TCPFieldCriteria extends ProtocolFieldCriteria
{
/*
 * TCP Variables
 * =============
 * $tcp_field[MAX][6]: stores all other tcp fields parameters/operators row
 *  - [][0] : (                            [][3] : field value
 *  - [][1] : windows, URP                 [][4] : (, )
 *  - [][2] : =, !=, <, <=, >, >=          [][5] : AND, OR
 *
 * $tcp_field_cnt: number of rows in the $tcp_field[][] structure
 */

   function TCPFieldCriteria(&$db, &$cs, $export_name, $element_cnt)
   {
	$tdb =& $db;
	$cs =& $cs;

      parent::ProtocolFieldCriteria($tdb, $cs, $export_name, $element_cnt,
                                    array ("tcp_win" => "window",
                                           "tcp_urp" => "urp",
                                           "tcp_seq" => "seq #",
                                           "tcp_ack" => "ack",
                                           "tcp_off" => "offset",
                                           "tcp_res" => "res",
                                           "tcp_csum" => "chksum"));
   }

   function PrintForm()
   {
      parent::PrintForm($this->valid_field_list, _DISPFIELD, _ADDTCPFIELD);
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
