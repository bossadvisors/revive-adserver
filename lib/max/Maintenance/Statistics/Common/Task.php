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

require_once MAX_PATH . '/lib/max/core/ServiceLocator.php';
require_once MAX_PATH . '/lib/max/core/Task.php';
require_once MAX_PATH . '/lib/max/Dal/Maintenance/Statistics/Factory.php';

/**
 * A abstract class, defining an interface for Maintenance Statistics Common
 * Task objects, to be collected and run using the MAX_Core_Task_Runner class.
 *
 * @abstract
 * @package    MaxMaintenance
 * @subpackage Statistics
 * @author     Andrew Hill <andrew@m3.net>
 */
class MAX_Maintenance_Statistics_Common_Task extends MAX_Core_Task
{

    /**
     * The "module" name of the maintenance statistics tasks
     *
     * @var string
     */
    var $module;

    /**
     * A reference to the object (that extends the
     * MAX_Maintenance_Statistics_Common class) that is running the task.
     *
     * @var MAX_Maintenance_Statistics_Common
     */
    var $oController;

    /**
     * The abstract class constructor, to be used by classes implementing
     * this class.
     */
    function MAX_Maintenance_Statistics_Common_Task()
    {
        $oServiceLocator = &ServiceLocator::instance();
        $this->oController = &$oServiceLocator->get('Maintenance_Statistics_Controller');
        if (!empty($this->oController->module)) {
            // Ensure that the required data access layer class is
            // registered in the service locator
            $serviceName = 'MAX_Dal_Maintenance_Statistics_' . $this->oController->module;
            if (!$oServiceLocator->get($serviceName)) {
                $oMDMSF = new MAX_Dal_Maintenance_Statistics_Factory();
                $oMaxDalMaintenanceStatistics = $oMDMSF->factory($this->oController->module);
                $oServiceLocator->register($serviceName, $oMaxDalMaintenanceStatistics);
            }
        }
    }

}