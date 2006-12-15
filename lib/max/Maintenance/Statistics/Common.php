<?php

/*
+---------------------------------------------------------------------------+
| Max Media Manager v0.3                                                    |
| =================                                                         |
|                                                                           |
| Copyright (c) 2003-2006 m3 Media Services Limited                         |
| For contact details, see: http://www.m3.net/                              |
|                                                                           |
| This program is free software; you can redistribute it and/or modify      |
| it under the terms of the GNU General Public License as published by      |
| the Free Software Foundation; either version 2 of the License, or         |
| (at your option) any later version.                                       |
|                                                                           |
| This program is distributed in the hope that it will be useful,           |
| but WITHOUT ANY WARRANTY; without even the implied warranty of            |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
| GNU General Public License for more details.                              |
|                                                                           |
| You should have received a copy of the GNU General Public License         |
| along with this program; if not, write to the Free Software               |
| Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA |
+---------------------------------------------------------------------------+
$Id$
*/

require_once MAX_PATH . '/lib/max/core/Task/Runner.php';

/**
 * An abstract class, defining common features of classes used for running
 * maintenance statistics tasks, for the various modules.
 *
 * @abstract
 * @package    MaxMaintenance
 * @subpackage Statistics
 * @author     Andrew Hill <andrew@m3.net>
 */
class MAX_Maintenance_Statistics_Common
{

    /**
     * The module for which the tasks are being run.
     *
     * @var string
     */
    var $module;

    /**
     * Text report of the details logged by the run tasks.
     *
     * @var string
     */
    var $report;

    /**
     * Should maintenance statistics update be performed on the basis
     * of the operation interval (true), or on an hourly basis (false)?
     *
     * @var boolean
     */
    var $updateUsingOI;

    /**
     * The date/time that the intermediate tables were last updated.
     *
     * @var PEAR::Date
     */
    var $lastDateIntermediate;

    /**
     * Should the intermediate tables be updated?
     *
     * @var boolean
     */
    var $updateIntermediate;

    /**
     * The date/time to update the intermediate tables to, if appropriate.
     *
     * @var PEAR::Date
     */
    var $updateIntermediateToDate;

    /**
     * The date/time that the final tables were last updated.
     *
     * @var PEAR::Date
     */
    var $lastDateFinal;

    /**
     * Should the final tables be updated?
     *
     * @var boolean
     */
    var $updateFinal;

    /**
     * The date/time to update the intermediate tables to, if appropriate.
     *
     * @var PEAR::Date
     */
    var $updateFinalToDate;

    /**
     * The local instance of the task runner.
     *
     * @var MAX_Core_Task_Runner
     */
    var $oTaskRunner;

    /**
     * The constructor method.
     */
    function MAX_Maintenance_Statistics_Common()
    {
        // Create the task runner object, for storing/running the tasks
        $this->oTaskRunner = new MAX_Core_Task_Runner();
    }

    /**
     * The method to run the tasks.
     */
    function updateStatistics()
    {
        // Run the required tasks
        $this->oTaskRunner->runTasks();
    }

}

?>
