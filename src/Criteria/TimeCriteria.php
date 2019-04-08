<?php

declare(strict_types=1);

namespace Vendi\BASE\Criteria;

class TimeCriteria extends MultipleElementCriteria
{
    /*
     * $time[MAX][10]: stores the date/time of the packet detection
     *  - [][0] : (                           [][5] : hour
     *  - [][1] : =, !=, <, <=, >, >=         [][6] : minute
     *  - [][2] : month                       [][7] : second
     *  - [][3] : day                         [][8] : (, )
     *  - [][4] : year                        [][9] : AND, OR
     *
     * $time_cnt : number of rows in the $time[][] structure
     */

    public function Clear()
    {
        /* clears the criteria */
    }

    public function SanitizeElement($i = null)
    {
        // Make copy of element array.
        $curArr = $this->criteria[$i];
        // Sanitize the element
        $this->criteria[$i][0] = @CleanVariable($curArr[0], VAR_OPAREN);
        $this->criteria[$i][1] = @CleanVariable($curArr[1], '', ['=', '!=', '<', '<=', '>', '>=']);
        $this->criteria[$i][2] = @CleanVariable($curArr[2], VAR_DIGIT);
        $this->criteria[$i][3] = @CleanVariable($curArr[3], VAR_DIGIT);
        $this->criteria[$i][4] = @CleanVariable($curArr[4], VAR_DIGIT);
        $this->criteria[$i][5] = @CleanVariable($curArr[5], VAR_DIGIT);
        $this->criteria[$i][6] = @CleanVariable($curArr[6], VAR_DIGIT);
        $this->criteria[$i][7] = @CleanVariable($curArr[7], VAR_DIGIT);
        $this->criteria[$i][8] = @CleanVariable($curArr[8], VAR_OPAREN | VAR_CPAREN);
        $this->criteria[$i][9] = @CleanVariable($curArr[9], '', ['AND', 'OR']);
        // Destroy the old copy
        unset($curArr);
    }

    public function PrintForm($field_list = null, $blank_field_string = null, $add_button_string = null)
    {
        for ($i = 0; $i < $this->criteria_cnt; ++$i) {
            if (!@is_array($this->criteria[$i])) {
                $this->criteria = [];
            }

            echo '<SELECT NAME="time[' . $i . '][0]"><OPTION VALUE=" " ' . chk_select(@$this->criteria[$i][0], ' ') . '>__';
            echo '                               <OPTION VALUE="("  ' . chk_select(@$this->criteria[$i][0], '(') . '>(</SELECT>';
            echo '<SELECT NAME="time[' . $i . '][1]"><OPTION VALUE=" "  ' . chk_select(@$this->criteria[$i][1], ' ') . '>' . _DISPTIME;
            echo '                               <OPTION VALUE="="  ' . chk_select(@$this->criteria[$i][1], '=') . '>=';
            echo '                               <OPTION VALUE="!=" ' . chk_select(@$this->criteria[$i][1], '!=') . '>!=';
            echo '                               <OPTION VALUE="<"  ' . chk_select(@$this->criteria[$i][1], '<') . '><';
            echo '                               <OPTION VALUE="<=" ' . chk_select(@$this->criteria[$i][1], '<=') . '><=';
            echo '                               <OPTION VALUE=">"  ' . chk_select(@$this->criteria[$i][1], '>') . '>>';
            echo '                               <OPTION VALUE=">=" ' . chk_select(@$this->criteria[$i][1], '>=') . '>>=</SELECT>';

            echo '<SELECT NAME="time[' . $i . '][2]"><OPTION VALUE=" "  ' . chk_select(@$this->criteria[$i][2], ' ') . '>' . _DISPMONTH;
            echo '                               <OPTION VALUE="01" ' . chk_select(@$this->criteria[$i][2], '01') . '>' . _SHORTJAN;
            echo '                               <OPTION VALUE="02" ' . chk_select(@$this->criteria[$i][2], '02') . '>' . _SHORTFEB;
            echo '                               <OPTION VALUE="03" ' . chk_select(@$this->criteria[$i][2], '03') . '>' . _SHORTMAR;
            echo '                               <OPTION VALUE="04" ' . chk_select(@$this->criteria[$i][2], '04') . '>' . _SHORTAPR;
            echo '                               <OPTION VALUE="05" ' . chk_select(@$this->criteria[$i][2], '05') . '>' . _SHORTMAY;
            echo '                               <OPTION VALUE="06" ' . chk_select(@$this->criteria[$i][2], '06') . '>' . _SHORTJUN;
            echo '                               <OPTION VALUE="07" ' . chk_select(@$this->criteria[$i][2], '07') . '>' . _SHORTJLY;
            echo '                               <OPTION VALUE="08" ' . chk_select(@$this->criteria[$i][2], '08') . '>' . _SHORTAUG;
            echo '                               <OPTION VALUE="09" ' . chk_select(@$this->criteria[$i][2], '09') . '>' . _SHORTSEP;
            echo '                               <OPTION VALUE="10" ' . chk_select(@$this->criteria[$i][2], '10') . '>' . _SHORTOCT;
            echo '                               <OPTION VALUE="11" ' . chk_select(@$this->criteria[$i][2], '11') . '>' . _SHORTNOV;
            echo '                               <OPTION VALUE="12" ' . chk_select(@$this->criteria[$i][2], '12') . '>' . _SHORTDEC . '</SELECT>';
            echo '<INPUT TYPE="text" NAME="time[' . $i . '][3]" SIZE=2 VALUE="' . htmlspecialchars(@$this->criteria[$i][3]) . '">';
            echo '<SELECT NAME="time[' . $i . '][4]">' . dispYearOptions(@$this->criteria[$i][4]) . '</SELECT>';

            echo '<INPUT TYPE="text" NAME="time[' . $i . '][5]" SIZE=2 VALUE="' . htmlspecialchars(@$this->criteria[$i][5]) . '"><B>:</B>';
            echo '<INPUT TYPE="text" NAME="time[' . $i . '][6]" SIZE=2 VALUE="' . htmlspecialchars(@$this->criteria[$i][6]) . '"><B>:</B>';
            echo '<INPUT TYPE="text" NAME="time[' . $i . '][7]" SIZE=2 VALUE="' . htmlspecialchars(@$this->criteria[$i][7]) . '">';

            echo '<SELECT NAME="time[' . $i . '][8]"><OPTION VALUE=" " ' . chk_select(@$this->criteria[$i][8], ' ') . '>__';
            echo '                               <OPTION VALUE="(" ' . chk_select(@$this->criteria[$i][8], '(') . '>(';
            echo '                               <OPTION VALUE=")" ' . chk_select(@$this->criteria[$i][8], ')') . '>)</SELECT>';
            echo '<SELECT NAME="time[' . $i . '][9]"><OPTION VALUE=" "   ' . chk_select(@$this->criteria[$i][9], ' ') . '>__';
            echo '                               <OPTION VALUE="OR" ' . chk_select(@$this->criteria[$i][9], 'OR') . '>' . _OR;
            echo '                               <OPTION VALUE="AND" ' . chk_select(@$this->criteria[$i][9], 'AND') . '>' . _AND . '</SELECT>';

            if ($i == $this->criteria_cnt - 1) {
                echo '    <INPUT TYPE="submit" NAME="submit" VALUE="' . _ADDTIME . '">';
            }
            echo '<BR>';
        }
    }

    public function ToSQL()
    {
        /* convert this criteria to SQL */
    }

    public function Description($human_fields = null)
    {
        $tmp = '';
        for ($i = 0; $i < $this->criteria_cnt; ++$i) {
            if (isset($this->criteria[$i][1]) && ' ' != $this->criteria[$i][1]) {
                $tmp = $tmp . '<CODE>' . htmlspecialchars($this->criteria[$i][0]) . ' time ' . htmlspecialchars($this->criteria[$i][1]) . ' [ ';

                /* date */
                if (' ' == $this->criteria[$i][2] && '' == $this->criteria[$i][3] && ' ' == $this->criteria[$i][4]) {
                    $tmp = $tmp . ' </CODE><I>any date</I><CODE>';
                } else {
                    $tmp = $tmp . ((' ' == $this->criteria[$i][2]) ? '* / ' : $this->criteria[$i][2] . ' / ') .
                           (('' == $this->criteria[$i][3]) ? '* / ' : $this->criteria[$i][3] . ' / ') .
                           ((' ' == $this->criteria[$i][4]) ? '*  ' : $this->criteria[$i][4] . ' ');
                }
                $tmp = $tmp . '] [ ';
                /* time */
                if ('' == $this->criteria[$i][5] && '' == $this->criteria[$i][6] && '' == $this->criteria[$i][7]) {
                    $tmp = $tmp . '</CODE><I>any time</I><CODE>';
                } else {
                    $tmp = $tmp . (('' == $this->criteria[$i][5]) ? '* : ' : $this->criteria[$i][5] . ' : ') .
                           (('' == $this->criteria[$i][6]) ? '* : ' : $this->criteria[$i][6] . ' : ') .
                           (('' == $this->criteria[$i][7]) ? '*  ' : $this->criteria[$i][7] . ' ');
                }
                $tmp = $tmp . $this->criteria[$i][8] . '] ' . $this->criteria[$i][9];
                $tmp = $tmp . '</CODE><BR>';
            }
        }
        if ('' != $tmp) {
            $tmp = $tmp . $this->cs->GetClearCriteriaString($this->export_name);
        }

        return $tmp;
    }
}
