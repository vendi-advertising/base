<?php

declare(strict_types=1);

namespace Vendi\BASE\Criteria;

class SensorCriteria extends SingleElementCriteria
{
    public function Init()
    {
        $this->criteria = '';
    }

    public function Clear()
    {
        /* clears the criteria */
    }

    public function SanitizeElement($i = null)
    {
        $this->criteria = CleanVariable($this->criteria, VAR_DIGIT);
    }

    public function PrintForm($field_list = null, $blank_field_string = null, $add_button_string = null)
    {
        global $debug_mode;

        // How many sensors do we have?
        $number_sensors = 0;
        $number_sensors_lst = $this->db->baseExecute('SELECT count(*) FROM sensor');
        $number_sensors_array = $number_sensors_lst->baseFetchRow();
        $number_sensors_lst->baseFreeRows();
        if (!isset($number_sensors_array)) {
            $mystr = '<BR>' . __FILE__ . '' . __LINE__ . ': $ERROR: number_sensors_array has not been set at all!<BR>';
            ErrorMessage($mystr);
            $number_sensors = 0;
        }

        if (null == $number_sensors_array || '' == $number_sensors_array) {
            $number_sensors = 0;
        } else {
            $number_sensors = $number_sensors_array[0];
        }

        if ($debug_mode > 1) {
            echo '$number_sensors = ' . $number_sensors . '<BR><BR>';
        }

        echo '<SELECT NAME="sensor">
             <OPTION VALUE=" " ' . chk_select($this->criteria, ' ') . '>' . _DISPANYSENSOR;

        $temp_sql = 'SELECT sid, hostname, interface, filter FROM sensor';
        $tmp_result = $this->db->baseExecute($temp_sql);

        for ($n = 0; $n < $number_sensors; ++$n) {
            $myrow = $tmp_result->baseFetchRow();

            if (!isset($myrow) || '' == $myrow || null == $myrow) {
                if ($n >= $number_sensors) {
                    break;
                } else {
                    next;
                }
            }

            echo '<OPTION VALUE="' . $myrow[0] . '" ' .
             chk_select($this->criteria, $myrow[0]) . '>' .
             '[' . $myrow[0] . '] ' .
             GetSensorName($myrow[0], $this->db);
        }
        $tmp_result->baseFreeRows();

        echo '</SELECT>&nbsp;&nbsp';
    }

    public function ToSQL()
    {
        /* convert this criteria to SQL */
    }

    public function Description($human_fields = null)
    {
        $tmp = '';

        if (' ' != $this->criteria && '' != $this->criteria) {
            $tmp = $tmp . _SENSOR . ' = [' . htmlentities($this->criteria) . '] ' .
               GetSensorName($this->criteria, $this->db) .
               $this->cs->GetClearCriteriaString($this->export_name) . '<BR>';
        }

        return $tmp;
    }
}
