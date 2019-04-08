<?php

declare(strict_types=1);

namespace Vendi\BASE;

/*******************************************************************************
** Purpose:  This file also contains the role object which is used to handle
** role management.
********************************************************************************
*/
class BaseRole
{
    public $db;

    public function __construct()
    {
        // Constructor
        global $DBlib_path, $DBtype, $db_connect_method, $alert_dbname, $alert_host,
                            $alert_port, $alert_user, $alert_password;
        $db = NewBASEDBConnection($DBlib_path, $DBtype);
        $db->baseDBConnect($db_connect_method, $alert_dbname, $alert_host,
                            $alert_port, $alert_user, $alert_password, 1);
        $this->db = $db;
    }

    public function addRole($roleid, $rolename, $desc)
    {
        //adds role
        $db = $this->db;
        $sql = "SELECT * FROM base_roles WHERE role_name = '" . $rolename . "'";
        $exists = $db->baseExecute($sql);
        if ($exists->baseRecordCount() > 0) {
            return _ROLEEXIST;
        }
        $sql = "SELECT * FROM base_roles WHERE role_id = '" . $roleid . "'";
        $exists = $db->baseExecute($sql);
        if ($exists->baseRecordCount() > 0) {
            return _ROLEIDEXIST;
        }
        $sql = 'INSERT INTO base_roles (role_id, role_name, role_desc)';
        $sql = $sql . 'VALUES (' . $roleid . ", '" . $rolename . "','" . $desc . "');";
        $db->baseExecute($sql, -1, -1, false);

        return _ROLEADDED;
    }

    public function returnEditRole($roleid)
    {
        /* returns an array of all Role's info
         * each array item is formatted as
         * array[0] = role_id|role_name|role_desc
        */

        $db = $this->db;
        $sql = 'SELECT role_id, role_name, role_desc ';
        $sql = $sql . "FROM base_roles WHERE role_id = '" . $roleid . "';";
        $result = $db->baseExecute($sql);

        $myrow = $result->baseFetchRow();
        $result->baseFreeRows();

        return $myrow;
    }

    public function updateRole($rolearray)
    {
        /* This function accepts an array in the following format
          $rolearray[0] = $roleid
          $rolearray[1] = $role_name
          $rolearray[2] = $role_desc
        */
        $db = $this->db;
        $sql = "UPDATE base_roles SET role_name = '" . $rolearray[1] . "', role_desc = '" . $rolearray[2] . "' ";
        $sql = $sql . "WHERE role_id = '" . $rolearray[0] . "'";
        $updated = $db->baseExecute($sql);

        return;
    }

    public function deleteRole($role)
    {
        //deletes the role
        $db = $this->db;
        $sql = "DELETE FROM base_roles WHERE role_id = '" . $role . "';";
        $deleted = $db->baseExecute($sql);

        return;
    }

    public function returnRoles()
    {
        /* returns an array of all Roles info
         * each array item is formatted as
         * array[] = role_id|role_name|role_desc
        */

        $db = $this->db;
        $sql = 'SELECT role_id, role_name, role_desc ';
        $sql = $sql . 'FROM base_roles ORDER BY role_id;';
        $result = $db->baseExecute($sql);

        $i = 0;
        while (($myrow = $result->baseFetchRow()) && ($i < $result->baseRecordCount())) {
            $rolearray[$i] = $myrow[0] . '|' . $myrow[1] . '|' . $myrow[2];
            ++$i;
        }
        $result->baseFreeRows();

        return $rolearray;
    }
}
