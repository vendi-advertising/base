<?php

declare(strict_types=1);

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

    public function __construct(&$db, &$cs, $export_name, $element_cnt)
    {
        $tdb = &$db;
        $cs = &$cs;

        parent::__construct($tdb, $cs, $export_name, $element_cnt,
                                    ['ip_tos' => 'TOS',
                                          'ip_ttl' => 'TTL',
                                          'ip_id' => 'ID',
                                          'ip_off' => 'offset',
                                          'ip_csum' => 'chksum',
                                          'ip_len' => 'length', ]);
    }

    public function PrintForm($field_list = null, $blank_field_string = null, $add_button_string = null)
    {
        parent::PrintForm($this->valid_field_list, _DISPFIELD, _ADDIPFIELD);
    }

    public function ToSQL()
    {
        /* convert this criteria to SQL */
    }

    public function Description($human_fields = null)
    {
        return parent::Description(array_merge(['' => '',
                                                       'LIKE' => _CONTAINS,
                                                       '=' => '=', ], $this->valid_field_list));
    }
}
