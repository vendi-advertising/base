<?php

declare(strict_types=1);

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

    public function __construct(&$db, &$cs, $export_name, $element_cnt)
    {
        $tdb = &$db;
        $cs = &$cs;

        parent::__construct($tdb, $cs, $export_name, $element_cnt,
                                    ['layer4_sport' => _SOURCEPORT,
                                           'layer4_dport' => _DESTPORT, ]);
    }

    public function PrintForm($field_list = null, $blank_field_string = null, $add_button_string = null)
    {
        parent::PrintForm($this->valid_field_list, _DISPPORT, _ADDTCPPORT);
    }

    public function ToSQL()
    {
        /* convert this criteria to SQL */
    }

    public function Description($human_fields = null)
    {
        return parent::Description(array_merge(['' => '',
                                                    '=' => '=', ], $this->valid_field_list));
    }
}
