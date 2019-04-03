<?php

namespace Vendi\BASE\Criteria;

class IPFieldCriteria extends ProtocolFieldCriteria
{
/*
 * $ip_field[MAX][6]: stores all other ip fields parameters/operators row
 *  - [][0] : (                            [][3] : field value
 *  - [][1] : TOS, TTL, ID, offset, length [][4] : (, )
 *  - [][2] : =, !=, <, <=, >, >=          [][5] : AND, OR
 *
 * $ip_field_cnt: number of rows in the $ip_field[][] structure
 */

   function IPFieldCriteria(&$db, &$cs, $export_name, $element_cnt)
   {
	$tdb =& $db;
	$cs =& $cs;

      parent::ProtocolFieldCriteria($tdb, $cs, $export_name, $element_cnt,
                                    array("ip_tos"  => "TOS",
                                          "ip_ttl"  => "TTL",
                                          "ip_id"   => "ID",
                                          "ip_off"  => "offset",
                                          "ip_csum" => "chksum",
                                          "ip_len"  => "length"));
   }

   function PrintForm()
   {
      parent::PrintForm($this->valid_field_list, _DISPFIELD, _ADDIPFIELD);
   }

   function ToSQL()
   {
     /* convert this criteria to SQL */
   }

   function Description()
   {
      return parent::Description( array_merge( array ( "" => "",
                                                       "LIKE" => _CONTAINS,
                                                       "=" => "="), $this->valid_field_list ) );
   }
}
