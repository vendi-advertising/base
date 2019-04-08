<?php

declare(strict_types=1);

namespace Vendi\BASE\Criteria;

class AlertGroupCriteria extends SingleElementCriteria
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
        echo '<SELECT NAME="ag">
             <OPTION VALUE=" " ' . chk_select($this->criteria, ' ') . '>' . _DISPANYAG;

        $temp_sql = 'SELECT ag_id, ag_name FROM acid_ag';
        $tmp_result = $this->db->baseExecute($temp_sql);
        if ($tmp_result) {
            while ($myrow = $tmp_result->baseFetchRow()) {
                echo '<OPTION VALUE="' . $myrow[0] . '" ' . chk_select($this->criteria, $myrow[0]) . '>' .
                 '[' . $myrow[0] . '] ' . htmlspecialchars($myrow[1]);
            }

            $tmp_result->baseFreeRows();
        }
        echo '</SELECT>&nbsp;&nbsp;';
    }

    public function ToSQL()
    {
        /* convert this criteria to SQL */
    }

    public function Description($human_fields = null)
    {
        $tmp = '';

        if (' ' != $this->criteria && '' != $this->criteria) {
            $tmp = $tmp . _ALERTGROUP . ' = [' . htmlentities($this->criteria) . '] ' . GetAGNameByID($this->criteria, $this->db) .
                    $this->cs->GetClearCriteriaString($this->export_name) . '<BR>';
        }

        return $tmp;
    }
}
