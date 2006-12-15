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
 * @subpackage StatsFields
 * @author     Matteo Beccati <matteo@beccati.com>
 */

require_once MAX_PATH . '/plugins/statsFields/statsFields.php';
require_once MAX_PATH . '/lib/max/Plugin/Translation.php';

/**
 * Stats fields plugin
 *
 * @package    MaxPlugin
 * @subpackage Plugins_StatsFields
 * @author     Matteo Beccati <matteo@beccati.com>
 * @abstract
 */
class Plugins_StatsFields_default_default extends Plugins_StatsFields
{
    /**
     * Constructor
     */
    function Plugins_StatsFields_default_default()
    {
        // Set ordering to a low value to move columns to the left
        $this->displayOrder = -10;

        // Set module and package because they aren't set when running the constructor method
        $this->module  = 'statsFields';
        $this->package = 'default';

        $this->_fields = array(
            'id'               => array('name'   => MAX_Plugin_Translation::translate('_ID', $this->module, $this->package),
                                        'short'  => MAX_Plugin_Translation::translate('ID', $this->module, $this->package),
                                        'pref'   => 'gui_column_id',
                                        'ctrl'     => 'StatsByEntityController',
                                        'format' => 'id'),
            'sum_requests'     => array('name'   => MAX_Plugin_Translation::translate('_Requests', $this->module, $this->package),
                                        'short'  => MAX_Plugin_Translation::translate('Requests', $this->module, $this->package),
                                        'pref'   => 'gui_column_requests',
                                        'active' => true,
                                        'format' => 'default'),
            'sum_views'        => array('name'   => MAX_Plugin_Translation::translate('_Impressions', $this->module, $this->package),
                                        'short'  => MAX_Plugin_Translation::translate('Impressions', $this->module, $this->package),
                                        'pref'   => 'gui_column_impressions',
                                        'active' => true,
                                        'format' => 'default'),
            'sum_clicks'       => array('name'   => MAX_Plugin_Translation::translate('_Clicks', $this->module, $this->package),
                                        'short'  => MAX_Plugin_Translation::translate('Clicks', $this->module, $this->package),
                                        'pref'   => 'gui_column_clicks',
                                        'active' => true,
                                        'format' => 'default'),
            'sum_ctr'          => array('name'   => MAX_Plugin_Translation::translate('_CTR', $this->module, $this->package),
                                        'short'  => MAX_Plugin_Translation::translate('CTR', $this->module, $this->package),
                                        'pref'   => 'gui_column_ctr',
                                        'format' => 'percent'),
            'sum_conversions'  => array('name'   => MAX_Plugin_Translation::translate('_Conversions', $this->module, $this->package),
                                        'short'  => MAX_Plugin_Translation::translate('Conversions', $this->module, $this->package),
                                        'pref'   => 'gui_column_conversions',
                                        'link'   => 'stats.php?entity=conversions&',
                                        'active' => true,
                                        'format' => 'default'),
            'sum_conversions_pending' => array(
                                        'name'   => MAX_Plugin_Translation::translate('_Pending conversions', $this->module, $this->package),
                                        'short'  => MAX_Plugin_Translation::translate('Pending conversions', $this->module, $this->package),
                                        'pref'   => 'gui_column_conversions_pending',
                                        'link'   => 'stats.php?entity=conversions&',
                                        'active' => true,
                                        'format' => 'default'),
            'sum_sr_views'     => array('name'   => MAX_Plugin_Translation::translate('_Impression SR', $this->module, $this->package),
                                        'short'  => MAX_Plugin_Translation::translate('Impression SR', $this->module, $this->package),
                                        'pref'   => 'gui_column_sr_views',
                                        'format' => 'percent'),
            'sum_sr_clicks'    => array('name'   => MAX_Plugin_Translation::translate('_Click SR', $this->module, $this->package),
                                        'short'  => MAX_Plugin_Translation::translate('Click SR', $this->module, $this->package),
                                        'pref'   => 'gui_column_sr_clicks',
                                        'format' => 'percent')
        );
    }

    /**
     * Return name of plugin
     *
     * @abstract
     * @return string A string describing the class.
     */
    function getName()
    {
        return 'Default columns';
    }

    function mergeData(&$aRows, $emptyRow, $method, $aParams)
    {
        $this->mergeConversions($aRows, $emptyRow, $method, $aParams);
    }

    /**
     * Generate CTR and SR ratios
     *
     * @static
     *
     * @param array Row of stats
     */
    function summarizeStats(&$row)
    {
        $row['sum_ctr']       = $row['sum_views']  ? $row['sum_clicks'] / $row['sum_views'] : 0;
        $row['sum_sr_views']  = $row['sum_views'] && isset($row['sum_conversions_'.MAX_CONNECTION_AD_IMPRESSION]) ?
                                    $row['sum_conversions_'.MAX_CONNECTION_AD_IMPRESSION] / $row['sum_views'] : 0;
        $row['sum_sr_clicks'] = $row['sum_clicks'] && isset($row['sum_conversions_'.MAX_CONNECTION_AD_CLICK]) ?
                                    $row['sum_conversions_'.MAX_CONNECTION_AD_CLICK] / $row['sum_clicks'] : 0;
    }
}

?>
