<?php

declare(strict_types=1);

namespace Vendi\BASE\Criteria;

class CriteriaState
{
    public $clear_criteria_name;
    public $clear_criteria_element;
    public $clear_url;
    public $clear_url_params;

    public $criteria;

    public function __construct($url, $params = '')
    {
        $this->clear_url = $url;
        $this->clear_url_params = $params;

        /* XXX-SEC */
        global $db, $debug_mode;

        $tdb = &$db;
        $obj = &$this;
        $this->criteria['sig'] = new SignatureCriteria($tdb, $obj, 'sig');
        $this->criteria['sig_class'] = new SignatureClassificationCriteria($tdb, $obj, 'sig_class');
        $this->criteria['sig_priority'] = new SignaturePriorityCriteria($tdb, $obj, 'sig_priority');
        $this->criteria['ag'] = new AlertGroupCriteria($tdb, $obj, 'ag');
        $this->criteria['sensor'] = new SensorCriteria($tdb, $obj, 'sensor');
        $this->criteria['time'] = new TimeCriteria($tdb, $obj, 'time', TIME_CFCNT);
        $this->criteria['ip_addr'] = new IPAddressCriteria($tdb, $obj, 'ip_addr', IPADDR_CFCNT);
        $this->criteria['layer4'] = new Layer4Criteria($tdb, $obj, 'layer4');
        $this->criteria['ip_field'] = new IPFieldCriteria($tdb, $obj, 'ip_field', PROTO_CFCNT);
        $this->criteria['tcp_port'] = new TCPPortCriteria($tdb, $obj, 'tcp_port', PROTO_CFCNT);
        $this->criteria['tcp_flags'] = new TCPFlagsCriteria($tdb, $obj, 'tcp_flags');
        $this->criteria['tcp_field'] = new TCPFieldCriteria($tdb, $obj, 'tcp_field', PROTO_CFCNT);
        $this->criteria['udp_port'] = new UDPPortCriteria($tdb, $obj, 'udp_port', PROTO_CFCNT);
        $this->criteria['udp_field'] = new UDPFieldCriteria($tdb, $obj, 'udp_field', PROTO_CFCNT);
        $this->criteria['icmp_field'] = new ICMPFieldCriteria($tdb, $obj, 'icmp_field', PROTO_CFCNT);
        $this->criteria['rawip_field'] = new TCPFieldCriteria($tdb, $obj, 'rawip_field', PROTO_CFCNT);
        $this->criteria['data'] = new DataCriteria($tdb, $obj, 'data', PAYLOAD_CFCNT);

        /*
         * For new criteria, add a call to the appropriate constructor here, and implement
         * the appropriate class in base_state_citems.inc.php
         */
    }

    public function InitState()
    {
        RegisterGlobalState();

        $valid_criteria_list = array_keys($this->criteria);

        foreach ($valid_criteria_list as $cname) {
            $this->criteria[$cname]->Init();
        }
    }

    public function ReadState()
    {
        RegisterGlobalState();

        /*
         * If the BACK button was clicked, shuffle the appropriate
         * criteria variables from the $back_list (history) array into
         * the current session ($_SESSION)
         */
        if ((1 == $GLOBALS['maintain_history']) &&
          (1 == ImportHTTPVar('back', VAR_DIGIT))) {
            PopHistory();
        }

        /*
         * Import, update and sanitize all persistant criteria variables
         */
        $valid_criteria_list = array_keys($this->criteria);
        foreach ($valid_criteria_list as $cname) {
            $this->criteria[$cname]->Import();
            $this->criteria[$cname]->Sanitize();
        }

        /*
         * Check whether criteria elements need to be cleared
         */
        $this->clear_criteria_name = ImportHTTPVar('clear_criteria', '',
                                                array_keys($this->criteria));
        $this->clear_criteria_element = ImportHTTPVar('clear_criteria_element', '',
                                                   array_keys($this->criteria));

        if ('' != $this->clear_criteria_name) {
            $this->ClearCriteriaStateElement($this->clear_criteria_name,
                                         $this->clear_criteria_element);
        }

        /*
         * Save the current criteria into $back_list (history)
         */
        if (1 == $GLOBALS['maintain_history']) {
            PushHistory();
        }
    }

    public function GetBackLink()
    {
        return PrintBackButton();
    }

    public function GetClearCriteriaString($name, $element = '')
    {
        return '&nbsp;&nbsp;<A HREF="' . $this->clear_url . '?clear_criteria=' . $name .
           '&amp;clear_criteria_element=' . $element . $this->clear_url_params . '">...' . _CLEAR . '...</A>';
    }

    public function ClearCriteriaStateElement($name, $element)
    {
        $valid_criteria_list = array_keys($this->criteria);

        if (in_array($name, $valid_criteria_list)) {
            ErrorMessage(_REMOVE . " '$name' " . _FROMCRIT);

            $this->criteria[$name]->Init();
        } else {
            ErrorMessage(_ERRCRITELEM);
        }
    }
}
