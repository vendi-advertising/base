<?php

declare(strict_types=1);

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

    public function __construct(&$db, &$cs, $export_name, $element_cnt)
    {
        $tdb = &$db;
        $cs = &$cs;

        parent::__construct($tdb, $cs, $export_name, $element_cnt,
                                    ['udp_len' => 'length',
                                           'udp_csum' => 'chksum', ]);
    }

    public function PrintForm($field_list = null, $blank_field_string = null, $add_button_string = null)
    {
        parent::PrintForm($this->valid_field_list, _DISPFIELD, _ADDUDPFIELD);
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
