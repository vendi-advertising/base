<?php

namespace Vendi\BASE;

/*******************************************************************************
**
** Purpose: Creates a user preferences object.  This object will allow the system
**  to track the preferences of the user.  It will also provide basic functions
**  like change password, etc.....
**
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/

//TODO: This class is invoked once but not used. cjh - 2019-04-05
class BaseUserPrefs
{
    var $db;

    function __construct()
    {
        // Constructor
        GLOBAL $DBlib_path, $DBtype, $db_connect_method, $alert_dbname, $alert_host,
                            $alert_port, $alert_user, $alert_password;
        $db = NewBASEDBConnection($DBlib_path, $DBtype);
        $db->baseDBConnect($db_connect_method, $alert_dbname, $alert_host,
                            $alert_port, $alert_user, $alert_password);
        $this->db = $db;
    }


}
