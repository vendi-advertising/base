<?php

declare(strict_types=1);

use Vendi\BASE\DatabaseTypes;
use Vendi\BASE\Exceptions\UnknownDatabaseException;

require_once __DIR__.'/includes/vendi_boot.php';

/*******************************************************************************
** Purpose: lookup routines for AG information
********************************************************************************
*/

function GetAGIDbyName($ag_name, $db)
{
    $sql = "SELECT ag_id FROM acid_ag WHERE ag_name='".$ag_name."'";

    $result = $db->baseExecute($sql, -1, -1, false);

    if ('' !== $db->baseErrorMessage()) {
        throw new UnknownDatabaseException(_ERRAGNAMESEARCH);
    }

    if ($result->baseRecordCount() < 1) {
        throw new UnknownDatabaseException(_ERRAGNAMEEXIST);
    }

    $myrow = $result->baseFetchRow();
    $ag_id = $myrow[0];
    $result->baseFreeRows();

    return $ag_id;
}

function GetAGNameByID($ag_id, $db)
{
    $sql = "SELECT ag_name FROM acid_ag WHERE ag_id='".$ag_id."'";

    $result = $db->baseExecute($sql, -1, -1, false);

    if ('' !== $db->baseErrorMessage()) {
        throw new UnknownDatabaseException(_ERRAGIDSEARCH);
    }

    if ($result->baseRecordCount() < 1) {
        throw new UnknownDatabaseException(_ERRAGNAMEEXIST);
    }

    $myrow = $result->baseFetchRow();
    $ag_name = $myrow[0];
    $result->baseFreeRows();

    return $ag_name;
}

function VerifyAGID($ag_id, $db)
{
    $sql = "SELECT ag_id FROM acid_ag WHERE ag_id='".$ag_id."'";
    $result = $db->baseExecute($sql);

    if ('' !== $db->baseErrorMessage()) {
        throw new UnknownDatabaseException(_ERRAGLOOKUP);
    }

    if ($result->baseRecordCount() < 1) {
        $result->baseFreeRows();
        return 0;
    }

    $result->baseFreeRows();
    return 1;
}

function CreateAG($db, $ag_name, $ag_desc)
{
    $sql = "INSERT INTO acid_ag (ag_name, ag_desc) VALUES ('".$ag_name."','".$ag_desc."');";
    $db->baseExecute($sql, -1, -1, false);

    if ('' !== $db->baseErrorMessage()) {
        throw new UnknownDatabaseException(_ERRAGINSERT);
    }

    $ag_id = $db->baseInsertID();
    /* The following code is a kludge and can cause errors.  Since it is not possible
     * to determine the last insert ID of the AG, we requery the DB to ascertain the ID
     * by matching on the ag_name and ag_desc.  -- rdd (1/23/2001)
     *
     * Modified code to only run the kludge if the dbtype is postgres.  Created a function
     * to use the actual insertid function if available and return -1 if no -- srh (02/01/2001)
     *
     * Transaction support is neccessary to get this absolutely correct, because using
     * an insert_id might break in a multi-user environment.  -- rdd (02/07/2001)
     */
    if (-1 === $ag_id) {
        $tmp_sql = "SELECT ag_id FROM acid_ag WHERE ag_name='".$ag_name."' AND ".
                 "ag_desc='".$ag_desc."'";
        if (DatabaseTypes::MSSQL === $db->DB_type) {
            $tmp_sql = "SELECT ag_id FROM acid_ag WHERE ag_name='".$ag_name."' AND ".
                    "ag_desc LIKE '".MssqlKludgeValue($ag_desc)."'";
        }
        $tmp_result = $db->baseExecute($tmp_sql);
        $myrow = $tmp_result->baseFetchRow();
        $ag_id = $myrow[0];
        $tmp_result->baseFreeRows();
    }

    return $ag_id;
}
