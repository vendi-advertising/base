<?php

declare(strict_types=1);
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
** Purpose: This file contains all of the common functions for the setup program
**
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/

//TODO: This class appears to be dead. cjh - 2019-04-05
class BaseSetup
{
    public $file;

    public function __construct($filename)
    {
        // Passes in the filename... This is for the CheckConfig
        $this->file = $filename;
    }

    public function CheckConfig($distConfigFile)
    {
        // Compares variables in distConfigFile to $this->file
    }

    public function writeConfig()
    {
        //writes the config file
    }

    public function displayConfig()
    {
        /*displays current config
         * Not to be confused with the end display on the
         * set up pages!
         */
    }
}
