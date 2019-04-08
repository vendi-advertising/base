<?php

declare(strict_types=1);

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

    public function __construct(&$db, &$cs, $export_name, $element_cnt)
    {
        $tdb = &$db;
        $cs = &$cs;

        parent::__construct($tdb, $cs, $export_name, $element_cnt,
                                    ['icmp_type' => 'type',
                                           'icmp_code' => 'code',
                                           'icmp_id' => 'id',
                                           'icmp_seq' => 'seq #',
                                           'icmp_csum' => 'chksum', ]);
    }

    public function PrintForm($field_list = null, $blank_field_string = null, $add_button_string = null)
    {
        parent::PrintForm($this->valid_field_list, _DISPFIELD, _ADDICMPFIELD);
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
