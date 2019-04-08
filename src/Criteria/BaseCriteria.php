<?php

declare(strict_types=1);

namespace Vendi\BASE\Criteria;

abstract class BaseCriteria
{
    public $criteria;
    public $export_name;

    public $db;
    public $cs;

    public function __construct(&$db, &$cs, $name)
    {
        $this->db = &$db;
        $this->cs = &$cs;

        $this->export_name = $name;
        $this->criteria = null;
    }

    abstract public function Init();

    /* imports criteria from POST, GET, or the session */
    abstract public function Import();

    /* clears the criteria */
    abstract public function Clear();

    /* clean/validate the criteria */
    abstract public function Sanitize();

    /* clean/validate the criteria */
    abstract public function SanitizeElement($i = null);

    /* prints the HTML form to input the criteria */
    abstract public function PrintForm($field_list = null, $blank_field_string = null, $add_button_string = null);

    /* returns the number of items in this form element  */
    abstract public function GetFormItemCnt();

    /* sets the number of items in this form element */
    abstract public function SetFormItemCnt($value);

    /* set the value of this criteria */
    abstract public function Set($value);

    /* returns the value of this criteria */
    abstract public function Get();

    /* convert this criteria to SQL */
    abstract public function ToSQL();

    /* generate human-readable description of this criteria */
    abstract public function Description($human_fields = null);

    /* returns if the criteria is empty */
    abstract public function isEmpty();
}
