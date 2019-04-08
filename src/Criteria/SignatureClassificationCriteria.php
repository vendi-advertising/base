<?php

declare(strict_types=1);

namespace Vendi\BASE\Criteria;

class SignatureClassificationCriteria extends SingleElementCriteria
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
        if ($this->db->baseGetDBversion() >= 103) {
            echo '<SELECT NAME="sig_class">
              <OPTION VALUE=" " ' . chk_select($this->criteria, ' ') . '>' . _DISPANYCLASS . '
              <OPTION VALUE="null" ' . chk_select($this->criteria, 'null') . '>-' . _UNCLASS . '-';

            $temp_sql = 'SELECT sig_class_id, sig_class_name FROM sig_class';
            $tmp_result = $this->db->baseExecute($temp_sql);
            if ($tmp_result) {
                while ($myrow = $tmp_result->baseFetchRow()) {
                    echo '<OPTION VALUE="' . $myrow[0] . '" ' . chk_select($this->criteria, $myrow[0]) . '>' .
                  $myrow[1];
                }

                $tmp_result->baseFreeRows();
            }
            echo '</SELECT>&nbsp;&nbsp';
        }
    }

    public function ToSQL()
    {
        /* convert this criteria to SQL */
    }

    public function Description($human_fields = null)
    {
        $tmp = '';

        if ($this->db->baseGetDBversion() >= 103) {
            if (' ' != $this->criteria && '' != $this->criteria) {
                if ('null' == $this->criteria) {
                    $tmp = $tmp . _SIGCLASS . ' = ' .
                              '<I>' . _UNCLASS . '</I><BR>';
                } else {
                    $tmp = $tmp . _SIGCLASS . ' = ' .
                              htmlentities(GetSigClassName($this->criteria, $this->db)) .
                              $this->cs->GetClearCriteriaString($this->export_name) . '<BR>';
                }
            }
        }

        return $tmp;
    }
}
