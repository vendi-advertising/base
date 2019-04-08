<?php

declare(strict_types=1);

namespace Vendi\BASE\Criteria;

abstract class MultipleElementCriteria extends BaseCriteria
{
    public $element_cnt;
    public $criteria_cnt;
    public $valid_field_list = [];

    public function __construct(&$db, &$cs, $export_name, $element_cnt, $field_list = [])
    {
        $tdb = &$db;
        $cs = &$cs;

        parent::__construct($tdb, $cs, $export_name);

        $this->element_cnt = $element_cnt;
        $this->criteria_cnt = 0;
        $this->valid_field_list = $field_list;
    }

    public function Init()
    {
        InitArray($this->criteria, $GLOBALS['MAX_ROWS'], $this->element_cnt, '');
        $this->criteria_cnt = 1;

        $_SESSION[$this->export_name . '_cnt'] = &$this->criteria_cnt;
    }

    public function Import()
    {
        $this->criteria = SetSessionVar($this->export_name);
        $this->criteria_cnt = SetSessionVar($this->export_name . '_cnt');

        $_SESSION[$this->export_name] = &$this->criteria;
        $_SESSION[$this->export_name . '_cnt'] = &$this->criteria_cnt;
    }

    public function Sanitize()
    {
        if (in_array('criteria', array_keys(get_object_vars($this)))) {
            for ($i = 0; $i < $this->element_cnt; ++$i) {
                if (isset($this->criteria[$i])) {
                    $this->SanitizeElement($i);
                }
            }
        }
    }

    public function SanitizeElement($i = null)
    {
    }

    public function GetFormItemCnt()
    {
        return $this->criteria_cnt;
    }

    public function SetFormItemCnt($value)
    {
        $this->criteria_cnt = $value;
    }

    public function AddFormItem(&$submit, $submit_value)
    {
        $this->criteria_cnt = &$this->criteria_cnt;
        AddCriteriaFormRow($submit, $submit_value, $this->criteria_cnt, $this->criteria, $this->element_cnt);
    }

    public function Set($value)
    {
        $this->criteria = $value;
    }

    public function Get()
    {
        return $this->criteria;
    }

    public function isEmpty()
    {
        if (0 == $this->criteria_cnt) {
            return true;
        } else {
            return false;
        }
    }

    public function PrintForm($field_list = null, $blank_field_string = null, $add_button_string = null)
    {
        for ($i = 0; $i < $this->criteria_cnt; ++$i) {
            if (!is_array($this->criteria[$i])) {
                $this->criteria = [];
            }

            echo '    <SELECT NAME="' . htmlspecialchars($this->export_name) . '[' . $i . '][0]">';
            echo '      <OPTION VALUE=" " ' . chk_select($this->criteria[$i][0], ' ') . '>__</OPTION>';
            echo '      <OPTION VALUE="(" ' . chk_select($this->criteria[$i][0], '(') . '>(</OPTION>';
            echo '    </SELECT>';

            echo '    <SELECT NAME="' . htmlspecialchars($this->export_name) . '[' . $i . '][1]">';
            echo '      <OPTION VALUE=" "      ' . chk_select($this->criteria[$i][1], ' ') . '>' . $blank_field_string . '</OPTION>';

            reset($field_list);
            foreach ($field_list as $field_name => $field_human_name) {
                echo '   <OPTION VALUE="' . $field_name . '" ' . chk_select($this->criteria[$i][1], $field_name) . '>' . $field_human_name . '</OPTION>';
            }
            echo '    </SELECT>';

            echo '    <SELECT NAME="' . htmlspecialchars($this->export_name) . '[' . $i . '][2]">';
            echo '      <OPTION VALUE="="  ' . chk_select($this->criteria[$i][2], '=') . '>=</OPTION>';
            echo '      <OPTION VALUE="!=" ' . chk_select($this->criteria[$i][2], '!=') . '>!=</OPTION>';
            echo '      <OPTION VALUE="<"  ' . chk_select($this->criteria[$i][2], '<') . '><</OPTION>';
            echo '      <OPTION VALUE="<=" ' . chk_select($this->criteria[$i][2], '<=') . '><=</OPTION>';
            echo '      <OPTION VALUE=">"  ' . chk_select($this->criteria[$i][2], '>') . '>></OPTION>';
            echo '      <OPTION VALUE=">=" ' . chk_select($this->criteria[$i][2], '>=') . '>>=</OPTION>';
            echo '    </SELECT>';

            echo '    <INPUT TYPE="text" NAME="' . htmlspecialchars($this->export_name) . '[' . $i . '][3]" SIZE=5 VALUE="' . htmlspecialchars($this->criteria[$i][3]) . '">';

            echo '    <SELECT NAME="' . htmlspecialchars($this->export_name) . '[' . $i . '][4]">';
            echo '      <OPTION VALUE=" " ' . chk_select($this->criteria[$i][4], ' ') . '>__</OPTION';
            echo '      <OPTION VALUE="(" ' . chk_select($this->criteria[$i][4], '(') . '>(</OPTION>';
            echo '      <OPTION VALUE=")" ' . chk_select($this->criteria[$i][4], ')') . '>)</OPTION>';
            echo '    </SELECT>';

            echo '    <SELECT NAME="' . htmlspecialchars($this->export_name) . '[' . $i . '][5]">';
            echo '      <OPTION VALUE=" "   ' . chk_select($this->criteria[$i][5], ' ') . '>__</OPTION>';
            echo '      <OPTION VALUE="OR" ' . chk_select($this->criteria[$i][5], 'OR') . '>' . _OR . '</OPTION>';
            echo '      <OPTION VALUE="AND" ' . chk_select($this->criteria[$i][5], 'AND') . '>' . _AND . '</OPTION>';
            echo '    </SELECT>';
            if ($i == $this->criteria_cnt - 1) {
                echo '    <INPUT TYPE="submit" NAME="submit" VALUE="' . htmlspecialchars($add_button_string) . '">';
            }
            echo '<BR>';
        }
    }

    public function Compact()
    {
        if ($this->isEmpty()) {
            $this->criteria = '';
            $_SESSION[$this->export_name] = &$this->criteria;
        }
    }
}
