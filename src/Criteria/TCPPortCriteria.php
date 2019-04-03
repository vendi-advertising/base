<?php

namespace Vendi\BASE\Criteria;

class TCPPortCriteria extends ProtocolFieldCriteria
{
/*
 * $tcp_port[MAX][6]: stores all port parameters/operators row
 *  - [][0] : (                            [][3] : port value
 *  - [][1] : Source Port, Dest Port       [][4] : (, )
 *  - [][2] : =, !=, <, <=, >, >=          [][5] : AND, OR
 *
 * $tcp_port_cnt: number of rows in the $tcp_port[][] structure
 */

   function TCPPortCriteria(&$db, &$cs, $export_name, $element_cnt)
   {
	$tdb =& $db;
	$cs =& $cs;

      parent::ProtocolFieldCriteria($tdb, $cs, $export_name, $element_cnt,
                                    array ("layer4_sport" => _SOURCEPORT,
                                           "layer4_dport" => _DESTPORT));
   }

   function PrintForm()
   {
      parent::PrintForm($this->valid_field_list, _DISPPORT, _ADDTCPPORT);
   }

   function ToSQL()
   {
     /* convert this criteria to SQL */
   }

   function Description()
   {
      return parent::Description(array_merge( array("" => "",
                                                    "=" => "="), $this->valid_field_list) );
   }
}
