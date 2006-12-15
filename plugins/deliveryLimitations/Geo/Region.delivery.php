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

/**
 * @package    MaxPlugin
 * @subpackage DeliveryLimitations
 * @author     Chris Nutting <chris@m3.net>
 * @author     Andrzej Swedrzynski <andrzej.swedrzynski@m3.net>
 */

require_once MAX_PATH . '/lib/max/Delivery/limitations.delivery.php';

/**
 * Check to see if this impression contains the valid region.
 *
 * @param string $limitation The region (or comma list of regions) limitation
 * @param string $op The operator (either '==' or '!=')
 * @param array $aParams An array of additional parameters to be checked
 * @return boolean Whether this impression's region passes this limitation's test.
 */
function MAX_checkGeo_Region($limitation, $op, $aParams = array())
{
    if (empty($aParams)) {
        $aParams = $GLOBALS['_MAX']['CLIENT_GEO'];
    }

    $aLimitation = explode('|', $limitation);
    $sCountry = $aLimitation[0];
    $sRegions = $aLimitation[1];

    if ($aParams && $aParams['region'] && $aParams['country_code']) {
        return MAX_limitationsMatchStringValue($aParams['country_code'], $sCountry, '==')
            && MAX_limitationsMatchArrayValue($aParams['region'], $sRegions, '=~');
    } else {
        return false; // Do not show the ad if user has no data about region and country.
    }
}

?>
