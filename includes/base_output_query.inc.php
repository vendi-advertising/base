<?php

require_once dirname(__DIR__) . '/includes/vendi_boot.php';

/*******************************************************************************
** Basic Analysis and Security Engine (BASE)
** Copyright (C) 2004 BASE Project Team
** Copyright (C) 2000 Carnegie Mellon University
**
** (see the file 'base_main.php' for license details)
**
** Project Lead: Kevin Johnson <kjohnson@secureideas.net>
**                Sean Muller <samwise_diver@users.sourceforge.net>
** Built upon work by Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
**
** Purpose: manages the output of Query results
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/
/** The below check is to make sure that the conf file has been loaded before this one....
 **  This should prevent someone from accessing the page directly. -- Kevin
 **/
defined( '_BASE_INC' ) or die( 'Accessing this file directly is not allowed.' );

include_once("$BASE_path/includes/base_constants.inc.php");

function qroReturnSelectALLCheck()
{
  return '<INPUT type=checkbox value="Select All" onClick="if (this.checked) SelectAll(); if (!this.checked) UnselectAll();">';
}

function qroPrintEntryHeader($prio=1, $color=0) {
 global $priority_colors;
 if($color == 1) {
        echo '<TR BGCOLOR="#'.$priority_colors[$prio].'">';
 } else {
        echo '<TR BGCOLOR="#'.((($prio % 2) == 0) ? "DDDDDD" : "FFFFFF").'">';
 }
}

function qroPrintEntry($value, $halign="center", $valign="top", $passthru="")
{
  echo "<TD align=\"".$halign."\" valign=\"".$valign."\" ".$passthru.">\n".
       "  $value\n".
       "</TD>\n\n";
}

function qroPrintEntryFooter()
{
  echo '</TR>';
}
