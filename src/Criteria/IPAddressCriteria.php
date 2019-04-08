<?php

declare(strict_types=1);

namespace Vendi\BASE\Criteria;

class IPAddressCriteria extends MultipleElementCriteria
{
    /*
     * $ip_addr[MAX][10]: stores an ip address parameters/operators row
     *  - [][0] : (                          [][5] : octet3 of address
     *  - [][1] : source, dest               [][6] : octet4 of address
     *  - [][2] : =, !=                      [][7] : network mask
     *  - [][3] : octet1 of address          [][8] : (, )
     *  - [][4] : octet2 of address          [][9] : AND, OR
     *
     * $ip_addr_cnt: number of rows in the $ip_addr[][] structure
     */

    public function __construct(&$db, &$cs, $export_name, $element_cnt)
    {
        $tdb = &$db;
        $cs = &$cs;

        parent::__construct($tdb, $cs, $export_name, $element_cnt,
                                      ['ip_src' => _SOURCE,
                                             'ip_dst' => _DEST,
                                             'ip_both' => _SORD, ]);
    }

    public function Import()
    {
        parent::Import();

        /* expand IP into octets */
        for ($i = 0; $i < $this->criteria_cnt; ++$i) {
            if ((isset($this->criteria[$i][3])) &&
             (ereg("([0-9]*)\.([0-9]*)\.([0-9]*)\.([0-9]*)", $this->criteria[$i][3]))) {
                $tmp_ip_str = $this->criteria[$i][7] = $this->criteria[$i][3];
                $this->criteria[$i][3] = strtok($tmp_ip_str, '.');
                $this->criteria[$i][4] = strtok('.');
                $this->criteria[$i][5] = strtok('.');
                $this->criteria[$i][6] = strtok('/');
                $this->criteria[$i][10] = strtok('');
            }
        }

        $_SESSION['ip_addr'] = &$this->criteria;
        $_SESSION['ip_addr_cnt'] = &$this->criteria_cnt;
    }

    public function Clear()
    {
        /* clears the criteria */
    }

    public function SanitizeElement($i = null)
    {
        $i = 0;
        // Make copy of old element array
        $curArr = $this->criteria[$i];
        // Sanitize element
        $this->criteria[$i][0] = @CleanVariable($curArr[0], VAR_OPAREN);
        $this->criteria[$i][1] = @CleanVariable($curArr[1], '', array_keys($this->valid_field_list));
        $this->criteria[$i][2] = @CleanVariable($curArr[2], '', ['=', '!=', '<', '<=', '>', '>=']);
        $this->criteria[$i][3] = @CleanVariable($curArr[3], VAR_DIGIT);
        $this->criteria[$i][4] = @CleanVariable($curArr[4], VAR_DIGIT);
        $this->criteria[$i][5] = @CleanVariable($curArr[5], VAR_DIGIT);
        $this->criteria[$i][6] = @CleanVariable($curArr[6], VAR_DIGIT);
        $this->criteria[$i][7] = @CleanVariable($curArr[7], VAR_DIGIT | VAR_PERIOD | VAR_FSLASH);
        $this->criteria[$i][8] = @CleanVariable($curArr[8], VAR_OPAREN | VAR_CPAREN);
        $this->criteria[$i][9] = @CleanVariable($curArr[9], '', ['AND', 'OR']);
        // Destroy copy
        unset($curArr);
    }

    public function PrintForm($field_list = null, $blank_field_string = null, $add_button_string = null)
    {
        for ($i = 0; $i < $this->criteria_cnt; ++$i) {
            if (!is_array(@$this->criteria[$i])) {
                $this->criteria = [];
            }

            echo '    <SELECT NAME="ip_addr[' . $i . '][0]"><OPTION VALUE=" " ' . chk_select(@$this->criteria[$i][0], ' ') . '>__';
            echo '                                      <OPTION VALUE="(" ' . chk_select(@$this->criteria[$i][0], '(') . '>(</SELECT>';
            echo '    <SELECT NAME="ip_addr[' . $i . '][1]">
                    <OPTION VALUE=" "      ' . chk_select(@$this->criteria[$i][1], ' ') . '>' . _DISPADDRESS . '
                    <OPTION VALUE="ip_src" ' . chk_select(@$this->criteria[$i][1], 'ip_src') . '>' . _SHORTSOURCE . '
                    <OPTION VALUE="ip_dst" ' . chk_select(@$this->criteria[$i][1], 'ip_dst') . '>' . _SHORTDEST . '
                    <OPTION VALUE="ip_both" ' . chk_select(@$this->criteria[$i][1], 'ip_both') . '>' . _SHORTSOURCEORDEST . '
                   </SELECT>';
            echo '    <SELECT NAME="ip_addr[' . $i . '][2]">
                    <OPTION VALUE="="  ' . chk_select(@$this->criteria[$i][2], '=') . '>=
                    <OPTION VALUE="!=" ' . chk_select(@$this->criteria[$i][2], '!=') . '>!=
                   </SELECT>';

            if (2 == $GLOBALS['ip_address_input']) {
                echo  '    <INPUT TYPE="text" NAME="ip_addr[' . $i . '][3]" SIZE=16 VALUE="' . htmlspecialchars(@$this->criteria[$i][7]) . '">';
            } else {
                echo '    <INPUT TYPE="text" NAME="ip_addr[' . $i . '][3]" SIZE=3 VALUE="' . htmlspecialchars(@$this->criteria[$i][3]) . '"><B>.</B>';
                echo '    <INPUT TYPE="text" NAME="ip_addr[' . $i . '][4]" SIZE=3 VALUE="' . htmlspecialchars(@$this->criteria[$i][4]) . '"><B>.</B>';
                echo '    <INPUT TYPE="text" NAME="ip_addr[' . $i . '][5]" SIZE=3 VALUE="' . htmlspecialchars(@$this->criteria[$i][5]) . '"><B>.</B>';
                echo '    <INPUT TYPE="text" NAME="ip_addr[' . $i . '][6]" SIZE=3 VALUE="' . htmlspecialchars(@$this->criteria[$i][6]) . '"><!--<B>/</B>';
                echo '    <INPUT TYPE="text" NAME="ip_addr[' . $i . '][7]" SIZE=3 VALUE="' . htmlspecialchars(@$this->criteria[$i][7]) . '">-->';
            }
            echo '    <SELECT NAME="ip_addr[' . $i . '][8]"><OPTION VALUE=" " ' . chk_select(@$this->criteria[$i][8], ' ') . '>__';
            echo '                                      <OPTION VALUE="(" ' . chk_select(@$this->criteria[$i][8], '(') . '>(';
            echo '                                      <OPTION VALUE=")" ' . chk_select(@$this->criteria[$i][8], ')') . '>)</SELECT>';
            echo '    <SELECT NAME="ip_addr[' . $i . '][9]"><OPTION VALUE=" "   ' . chk_select(@$this->criteria[$i][9], ' ') . '>__';
            echo '                                      <OPTION VALUE="OR" ' . chk_select(@$this->criteria[$i][9], 'OR') . '>' . _OR;
            echo '                                      <OPTION VALUE="AND" ' . chk_select(@$this->criteria[$i][9], 'AND') . '>' . _AND . '</SELECT>';
            if ($i == $this->criteria_cnt - 1) {
                echo '    <INPUT TYPE="submit" NAME="submit" VALUE="' . _ADDADDRESS . '">';
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
        $human_fields['ip_src'] = _SOURCE;
        $human_fields['ip_dst'] = _DEST;
        $human_fields['ip_both'] = _SORD;
        $human_fields[''] = '';
        $human_fields['LIKE'] = _CONTAINS;
        $human_fields['='] = '=';

        $tmp2 = '';

        for ($i = 0; $i < $this->criteria_cnt; ++$i) {
            $tmp = '';
            if (isset($this->criteria[$i][3]) && '' != $this->criteria[$i][3]) {
                $tmp = $tmp . $this->criteria[$i][3];
                if ('' != $this->criteria[$i][4]) {
                    $tmp = $tmp . '.' . $this->criteria[$i][4];
                    if ('' != $this->criteria[$i][5]) {
                        $tmp = $tmp . '.' . $this->criteria[$i][5];
                        if ('' != $this->criteria[$i][6]) {
                            if (NULL_IP == ($this->criteria[$i][3] . '.' . $this->criteria[$i][4] . '.' .
                        $this->criteria[$i][5] . '.' . $this->criteria[$i][6])) {
                                $tmp = ' unknown ';
                            } else {
                                $tmp = $tmp . '.' . $this->criteria[$i][6];
                            }
                        } else {
                            $tmp = $tmp . '.*';
                        }
                    } else {
                        $tmp = $tmp . '.*.*';
                    }
                } else {
                    $tmp = $tmp . '.*.*.*';
                }
            }
            /* Make sure that the IP isn't blank */
            if ('' != $tmp) {
                $mask = '';
                if ('' != $this->criteria[$i][10]) {
                    $mask = '/' . $this->criteria[$i][10];
                }

                $tmp2 = $tmp2 . $this->criteria[$i][0] .
                     $human_fields[($this->criteria[$i][1])] . ' ' . $this->criteria[$i][2] .
                     ' ' . $tmp . ' ' . $this->criteria[$i][8] . ' ' . $this->criteria[$i][9] . $mask .
                     $this->cs->GetClearCriteriaString($this->export_name) . '<BR>';
            }
        }

        return $tmp2;
    }
}
