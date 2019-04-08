<?php

declare(strict_types=1);

namespace Vendi\BASE\Criteria;

class Layer4Criteria extends SingleElementCriteria
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
        $this->criteria = CleanVariable($this->criteria, '', ['UDP', 'TCP', 'ICMP', 'RawIP']);
    }

    public function PrintForm($field_list = null, $blank_field_string = null, $add_button_string = null)
    {
        if ('' != $this->criteria) {
            echo '<INPUT TYPE="submit" NAME="submit" VALUE="' . _NOLAYER4 . '"> &nbsp';
        }
        if ('TCP' == $this->criteria) {
            echo '
           <INPUT TYPE="submit" NAME="submit" VALUE="UDP"> &nbsp
           <INPUT TYPE="submit" NAME="submit" VALUE="ICMP">';
        } elseif ('UDP' == $this->criteria) {
            echo '
           <INPUT TYPE="submit" NAME="submit" VALUE="TCP"> &nbsp
           <INPUT TYPE="submit" NAME="submit" VALUE="ICMP">';
        } elseif ('ICMP' == $this->criteria) {
            echo '
           <INPUT TYPE="submit" NAME="submit" VALUE="TCP"> &nbsp
           <INPUT TYPE="submit" NAME="submit" VALUE="UDP">';
        } else {
            echo '
           <INPUT TYPE="submit" NAME="submit" VALUE="TCP"> &nbsp
           <INPUT TYPE="submit" NAME="submit" VALUE="UDP">
           <INPUT TYPE="submit" NAME="submit" VALUE="ICMP">';
        }
    }

    public function ToSQL()
    {
        /* convert this criteria to SQL */
    }

    public function Description($human_fields = null)
    {
        if ('TCP' == $this->criteria) {
            return _QCTCPCRIT;
        } elseif ('UDP' == $this->criteria) {
            return _QCUDPCRIT;
        } elseif ('ICMP' == $this->criteria) {
            return _QCICMPCRIT;
        } else {
            return _QCLAYER4CRIT;
        }
    }
}
