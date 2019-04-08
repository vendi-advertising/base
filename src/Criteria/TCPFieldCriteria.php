<?php

declare(strict_types=1);

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

    public function __construct(&$db, &$cs, $export_name, $element_cnt)
    {
        $tdb = &$db;
        $cs = &$cs;

        parent::__construct($tdb, $cs, $export_name, $element_cnt,
                                    ['tcp_win' => 'window',
                                           'tcp_urp' => 'urp',
                                           'tcp_seq' => 'seq #',
                                           'tcp_ack' => 'ack',
                                           'tcp_off' => 'offset',
                                           'tcp_res' => 'res',
                                           'tcp_csum' => 'chksum', ]);
    }

    public function PrintForm($field_list = null, $blank_field_string = null, $add_button_string = null)
    {
        parent::PrintForm($this->valid_field_list, _DISPFIELD, _ADDTCPFIELD);
    }

    public function ToSQL()
    {
        /* convert this criteria to SQL */
    }

    public function Description($human_fields = null)
    {
        return parent::Description(array_merge(['' => ''], $this->valid_field_list));
    }
}
