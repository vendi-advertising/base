<?php

declare(strict_types=1);

namespace Vendi\BASE\Criteria;

abstract class SingleElementCriteria extends BaseCriteria
{
    public function Import()
    {
        $this->criteria = SetSessionVar($this->export_name);

        $_SESSION[$this->export_name] = &$this->criteria;
    }

    public function Sanitize()
    {
        $this->SanitizeElement();
    }

    public function GetFormItemCnt()
    {
        return -1;
    }

    public function SetFormItemCnt($value)
    {
        //NOOP
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
        if ('' == $this->criteria) {
            return true;
        } else {
            return false;
        }
    }
}
