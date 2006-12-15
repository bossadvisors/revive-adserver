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

require_once MAX_PATH . '/lib/max/Maintenance/Priority/DeliveryLimitation.php';
require_once MAX_PATH . '/lib/pear/Date.php';

/**
 * A class for testing the MAX_Maintenance_Priority_DeliveryLimitation class.
 *
 * @package    MaxMaintenance
 * @subpackage TestSuite
 * @author     James Floyd<james@m3.net>
 */
class Maintenance_TestOfPriorityLimitation extends UnitTestCase
{

    /**
     * The constructor method.
     */
    function Maintenance_TestOfPriorityLimitation()
    {
        $this->UnitTestCase();
        Mock::generatePartial(
            'MAX_Maintenance_Priority_DeliveryLimitation',
            'Partial_MockMAX_Maintenance_Priority_DeliveryLimitation',
            array('_getOperationInterval')
        );
    }

    /**
     * A method to test the MAX_Maintenance_Priority_DeliveryLimitation
     * constructor method.
     */
    function testConstructor()
    {
        $aDeliveryLimitations = array(
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Hour',
                'comparison'     => '==',
                'data'           => '1,7,18,23',
                'executionorder' => 1
            ),
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Day',
                'comparison'     => '==',
                'data'           => '1',
                'executionorder' => 3
            ),
            array(
                'ad_id'          => 1,
                'logical'        => 'or',
                'type'           => 'Time:Date',
                'comparison'     => '==',
                'data'           => '2005:10:10 00:00:00',
                'executionorder' => 2
            ),
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Client:IP',
                'comparison'     => '==',
                'data'           => '192.168.0.1',
                'executionorder' => 0
            ),
        );
        $oDeliveryLimitationManager = new Partial_MockMAX_Maintenance_Priority_DeliveryLimitation($this);
        $oDeliveryLimitationManager->setReturnValue('_getOperationInterval', true);
        $oDeliveryLimitationManager->MAX_Maintenance_Priority_DeliveryLimitation($aDeliveryLimitations);
        // Test that the different limitations have been set correctly
        $this->assertTrue(count($oDeliveryLimitationManager->aRules) == 4);
        $this->assertTrue(is_a($oDeliveryLimitationManager->aRules[0], 'MAX_Maintenance_Priority_DeliveryLimitation_Empty'));
        $this->assertTrue(is_a($oDeliveryLimitationManager->aRules[1], 'MAX_Maintenance_Priority_DeliveryLimitation_Hour'));
        $this->assertTrue(is_a($oDeliveryLimitationManager->aRules[2], 'MAX_Maintenance_Priority_DeliveryLimitation_Date'));
        $this->assertTrue(is_a($oDeliveryLimitationManager->aRules[3], 'MAX_Maintenance_Priority_DeliveryLimitation_Day'));
        $this->assertTrue(count($oDeliveryLimitationManager->aOperationGroups) == 2);
        $this->assertTrue(count($oDeliveryLimitationManager->aOperationGroups[0]) == 1);
        $this->assertTrue(is_a($oDeliveryLimitationManager->aOperationGroups[0][0], 'MAX_Maintenance_Priority_DeliveryLimitation_Hour'));
        $this->assertTrue(count($oDeliveryLimitationManager->aOperationGroups[1]) == 2);
        $this->assertTrue(is_a($oDeliveryLimitationManager->aOperationGroups[1][0], 'MAX_Maintenance_Priority_DeliveryLimitation_Date'));
        $this->assertTrue(is_a($oDeliveryLimitationManager->aOperationGroups[1][1], 'MAX_Maintenance_Priority_DeliveryLimitation_Day'));
    }

    /**
     * A method to test the _getOperationGroupCount() method.
     */
    function test_getOperationGroupCount()
    {
        $aDeliveryLimitations = array(
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Hour',
                'comparison'     => '==',
                'data'           => '1,7,18,23',
                'executionorder' => 1
            ),
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Day',
                'comparison'     => '==',
                'data'           => '1',
                'executionorder' => 3
            ),
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Date',
                'comparison'     => '==',
                'data'           => '2005:10:10 00:00:00',
                'executionorder' => 2
            ),
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Client:IP',
                'comparison'     => '==',
                'data'           => '192.168.0.1',
                'executionorder' => 0
            ),
        );
        $oDeliveryLimitationManager = new Partial_MockMAX_Maintenance_Priority_DeliveryLimitation($this);
        $oDeliveryLimitationManager->setReturnValue('_getOperationInterval', true);
        $oDeliveryLimitationManager->MAX_Maintenance_Priority_DeliveryLimitation($aDeliveryLimitations);
        $this->assertEqual($oDeliveryLimitationManager->_getOperationGroupCount(), 1);

        $aDeliveryLimitations = array(
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Hour',
                'comparison'     => '==',
                'data'           => '1,7,18,23',
                'executionorder' => 1
            ),
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Day',
                'comparison'     => '==',
                'data'           => '1',
                'executionorder' => 3
            ),
            array(
                'ad_id'          => 1,
                'logical'        => 'or',
                'type'           => 'Time:Date',
                'comparison'     => '==',
                'data'           => '2005:10:10 00:00:00',
                'executionorder' => 2
            ),
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Client:IP',
                'comparison'     => '==',
                'data'           => '192.168.0.1',
                'executionorder' => 0
            ),
        );
        $oDeliveryLimitationManager = new Partial_MockMAX_Maintenance_Priority_DeliveryLimitation($this);
        $oDeliveryLimitationManager->setReturnValue('_getOperationInterval', true);
        $oDeliveryLimitationManager->MAX_Maintenance_Priority_DeliveryLimitation($aDeliveryLimitations);
        $this->assertEqual($oDeliveryLimitationManager->_getOperationGroupCount(), 2);
    }

    /**
     * A method to test the deliveryBlocked() method.
     *
     * Test 1: Test a simple, single delivery limitation case, where delivery is NOT blocked.
     * Test 2: Test a simple, single delivery limitation case, where delivery IS blocked.
     *
     * Test 3: Test a simple, dual AND delivery limitation case, is NOT blocked in either limitation.
     * Test 4: Test a simple, dual AND delivery limitation case, is IS blocked in ONE limitation.
     * Test 5: Test a simple, dual AND delivery limitation case, is IS blocked in BOTH limitations.
     *
     * Test 6: Test a simple, dual OR delivery limitation case, is NOT blocked in either limitation.
     * Test 7: Test a simple, dual OR delivery limitation case, is IS blocked in ONE limitation.
     * Test 8: Test a simple, dual OR delivery limitation case, is IS blocked in BOTH limitations.
     *
     * Test 9: Test several complex, multi-delivery limitation cases.
     */
    function testDeliveryBlocked()
    {
        // Test 1
        $oDate = new Date('2006-02-08 07:05:00');
        $aDeliveryLimitations = array(
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Hour',
                'comparison'     => '==',
                'data'           => '1,7,18,23',
                'executionorder' => 0
            )
        );
        $oDeliveryLimitationManager = new Partial_MockMAX_Maintenance_Priority_DeliveryLimitation($this);
        $oDeliveryLimitationManager->setReturnValue('_getOperationInterval', true);
        $oDeliveryLimitationManager->MAX_Maintenance_Priority_DeliveryLimitation($aDeliveryLimitations);
        $result = $oDeliveryLimitationManager->deliveryBlocked($oDate);
        $this->assertFalse($result);

        // Test 2
        $oDate = new Date('2006-02-08 10:05:00');
        $aDeliveryLimitations = array(
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Hour',
                'comparison'     => '==',
                'data'           => '1,7,18,23',
                'executionorder' => 0
            )
        );
        $oDeliveryLimitationManager = new Partial_MockMAX_Maintenance_Priority_DeliveryLimitation($this);
        $oDeliveryLimitationManager->setReturnValue('_getOperationInterval', true);
        $oDeliveryLimitationManager->MAX_Maintenance_Priority_DeliveryLimitation($aDeliveryLimitations);
        $result = $oDeliveryLimitationManager->deliveryBlocked($oDate);
        $this->assertTrue($result);

        // Test 3
        $oDate = new Date('2006-02-08 07:05:00');
        $aDeliveryLimitations = array(
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Hour',
                'comparison'     => '==',
                'data'           => '1,7,18,23',
                'executionorder' => 0
            ),
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Date',
                'comparison'     => '==',
                'data'           => '2006-02-08',
                'executionorder' => 1
            )
        );
        $oDeliveryLimitationManager = new Partial_MockMAX_Maintenance_Priority_DeliveryLimitation($this);
        $oDeliveryLimitationManager->setReturnValue('_getOperationInterval', true);
        $oDeliveryLimitationManager->MAX_Maintenance_Priority_DeliveryLimitation($aDeliveryLimitations);
        $result = $oDeliveryLimitationManager->deliveryBlocked($oDate);
        $this->assertFalse($result);

        // Test 4
        $oDate = new Date('2006-02-08 10:05:00');
        $aDeliveryLimitations = array(
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Hour',
                'comparison'     => '==',
                'data'           => '1,7,18,23',
                'executionorder' => 0
            ),
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Date',
                'comparison'     => '==',
                'data'           => '2006-02-08',
                'executionorder' => 1
            )
        );
        $oDeliveryLimitationManager = new Partial_MockMAX_Maintenance_Priority_DeliveryLimitation($this);
        $oDeliveryLimitationManager->setReturnValue('_getOperationInterval', true);
        $oDeliveryLimitationManager->MAX_Maintenance_Priority_DeliveryLimitation($aDeliveryLimitations);
        $result = $oDeliveryLimitationManager->deliveryBlocked($oDate);
        $this->assertTrue($result);

        // Test 5
        $oDate = new Date('2006-02-09 10:05:00');
        $aDeliveryLimitations = array(
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Hour',
                'comparison'     => '==',
                'data'           => '1,7,18,23',
                'executionorder' => 0
            ),
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Date',
                'comparison'     => '==',
                'data'           => '2006-02-08',
                'executionorder' => 1
            )
        );
        $oDeliveryLimitationManager = new Partial_MockMAX_Maintenance_Priority_DeliveryLimitation($this);
        $oDeliveryLimitationManager->setReturnValue('_getOperationInterval', true);
        $oDeliveryLimitationManager->MAX_Maintenance_Priority_DeliveryLimitation($aDeliveryLimitations);
        $result = $oDeliveryLimitationManager->deliveryBlocked($oDate);
        $this->assertTrue($result);

        // Test 6
        $oDate = new Date('2006-02-08 07:05:00');
        $aDeliveryLimitations = array(
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Hour',
                'comparison'     => '==',
                'data'           => '1,7,18,23',
                'executionorder' => 0
            ),
            array(
                'ad_id'          => 1,
                'logical'        => 'or',
                'type'           => 'Time:Date',
                'comparison'     => '==',
                'data'           => '2006-02-08',
                'executionorder' => 1
            )
        );
        $oDeliveryLimitationManager = new Partial_MockMAX_Maintenance_Priority_DeliveryLimitation($this);
        $oDeliveryLimitationManager->setReturnValue('_getOperationInterval', true);
        $oDeliveryLimitationManager->MAX_Maintenance_Priority_DeliveryLimitation($aDeliveryLimitations);
        $result = $oDeliveryLimitationManager->deliveryBlocked($oDate);
        $this->assertFalse($result);

        // Test 7
        $oDate = new Date('2006-02-08 10:05:00');
        $aDeliveryLimitations = array(
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Hour',
                'comparison'     => '==',
                'data'           => '1,7,18,23',
                'executionorder' => 0
            ),
            array(
                'ad_id'          => 1,
                'logical'        => 'or',
                'type'           => 'Time:Date',
                'comparison'     => '==',
                'data'           => '2006-02-08',
                'executionorder' => 1
            )
        );
        $oDeliveryLimitationManager = new Partial_MockMAX_Maintenance_Priority_DeliveryLimitation($this);
        $oDeliveryLimitationManager->setReturnValue('_getOperationInterval', true);
        $oDeliveryLimitationManager->MAX_Maintenance_Priority_DeliveryLimitation($aDeliveryLimitations);
        $result = $oDeliveryLimitationManager->deliveryBlocked($oDate);
        $this->assertFalse($result);

        // Test 8
        $oDate = new Date('2006-02-09 10:05:00');
        $aDeliveryLimitations = array(
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Hour',
                'comparison'     => '==',
                'data'           => '1,7,18,23',
                'executionorder' => 0
            ),
            array(
                'ad_id'          => 1,
                'logical'        => 'or',
                'type'           => 'Time:Date',
                'comparison'     => '==',
                'data'           => '2006-02-08',
                'executionorder' => 1
            )
        );
        $oDeliveryLimitationManager = new Partial_MockMAX_Maintenance_Priority_DeliveryLimitation($this);
        $oDeliveryLimitationManager->setReturnValue('_getOperationInterval', true);
        $oDeliveryLimitationManager->MAX_Maintenance_Priority_DeliveryLimitation($aDeliveryLimitations);
        $result = $oDeliveryLimitationManager->deliveryBlocked($oDate);
        $this->assertTrue($result);

        // Test 9
        $oDate = new Date('2006-02-08 10:05:00');
        $aDeliveryLimitations = array(
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Hour',
                'comparison'     => '==',
                'data'           => '1,7,18,23',
                'executionorder' => 0
            ),
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Date',
                'comparison'     => '==',
                'data'           => '2006-02-08',
                'executionorder' => 1
            ),
            array(
                'ad_id'          => 1,
                'logical'        => 'or',
                'type'           => 'Time:Date',
                'comparison'     => '==',
                'data'           => '2006-02-09',
                'executionorder' => 2
            ),
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Client:IP',
                'comparison'     => '==',
                'data'           => '192.168.0.1',
                'executionorder' => 3
            )
        );
        $oDeliveryLimitationManager = new Partial_MockMAX_Maintenance_Priority_DeliveryLimitation($this);
        $oDeliveryLimitationManager->setReturnValue('_getOperationInterval', true);
        $oDeliveryLimitationManager->MAX_Maintenance_Priority_DeliveryLimitation($aDeliveryLimitations);
        $result = $oDeliveryLimitationManager->deliveryBlocked($oDate);
        $this->assertTrue($result);

        $oDate = new Date('2006-02-09 10:05:00');
        $aDeliveryLimitations = array(
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Hour',
                'comparison'     => '==',
                'data'           => '1,7,18,23',
                'executionorder' => 0
            ),
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Date',
                'comparison'     => '==',
                'data'           => '2006-02-08',
                'executionorder' => 1
            ),
            array(
                'ad_id'          => 1,
                'logical'        => 'or',
                'type'           => 'Time:Date',
                'comparison'     => '==',
                'data'           => '2006-02-09',
                'executionorder' => 2
            ),
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Client:IP',
                'comparison'     => '==',
                'data'           => '192.168.0.1',
                'executionorder' => 3
            )
        );
        $oDeliveryLimitationManager = new Partial_MockMAX_Maintenance_Priority_DeliveryLimitation($this);
        $oDeliveryLimitationManager->setReturnValue('_getOperationInterval', true);
        $oDeliveryLimitationManager->MAX_Maintenance_Priority_DeliveryLimitation($aDeliveryLimitations);
        $result = $oDeliveryLimitationManager->deliveryBlocked($oDate);
        $this->assertFalse($result);

        $oDate = new Date('2006-02-10 23:05:00');
        $aDeliveryLimitations = array(
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Hour',
                'comparison'     => '==',
                'data'           => '1,7,18,23',
                'executionorder' => 0
            ),
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Date',
                'comparison'     => '==',
                'data'           => '2006-02-08',
                'executionorder' => 1
            ),
            array(
                'ad_id'          => 1,
                'logical'        => 'or',
                'type'           => 'Time:Date',
                'comparison'     => '==',
                'data'           => '2006-02-09',
                'executionorder' => 2
            ),
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Client:IP',
                'comparison'     => '==',
                'data'           => '192.168.0.1',
                'executionorder' => 3
            )
        );
        $oDeliveryLimitationManager = new Partial_MockMAX_Maintenance_Priority_DeliveryLimitation($this);
        $oDeliveryLimitationManager->setReturnValue('_getOperationInterval', true);
        $oDeliveryLimitationManager->MAX_Maintenance_Priority_DeliveryLimitation($aDeliveryLimitations);
        $result = $oDeliveryLimitationManager->deliveryBlocked($oDate);
        $this->assertTrue($result);

        $oDate = new Date('2006-02-10 23:05:00');
        $aDeliveryLimitations = array(
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Hour',
                'comparison'     => '==',
                'data'           => '1,7,18,23',
                'executionorder' => 0
            ),
            array(
                'ad_id'          => 1,
                'logical'        => 'or',
                'type'           => 'Time:Date',
                'comparison'     => '==',
                'data'           => '2006-02-08',
                'executionorder' => 1
            ),
            array(
                'ad_id'          => 1,
                'logical'        => 'or',
                'type'           => 'Time:Date',
                'comparison'     => '==',
                'data'           => '2006-02-09',
                'executionorder' => 2
            ),
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Client:IP',
                'comparison'     => '==',
                'data'           => '192.168.0.1',
                'executionorder' => 3
            )
        );
        $oDeliveryLimitationManager = new Partial_MockMAX_Maintenance_Priority_DeliveryLimitation($this);
        $oDeliveryLimitationManager->setReturnValue('_getOperationInterval', true);
        $oDeliveryLimitationManager->MAX_Maintenance_Priority_DeliveryLimitation($aDeliveryLimitations);
        $result = $oDeliveryLimitationManager->deliveryBlocked($oDate);
        $this->assertFalse($result);
    }

    /**
     * A method to test the getBlockedOperationIntervalCount() method.
     *
     * Test 1: Test with an equal blocking limitation.
     * Test 2: Test with a non-equal blocking limitation.
     */
    function testGetBlockedOperationIntervalCount()
    {
        $conf = &$GLOBALS['_MAX']['CONF'];
        $conf['maintenance']['operationInterval'] = 60;
        $oNowDate = new Date('2006-02-08 07:05:00');
        $oPlacementEndDate = new Date('2006-02-10');

        // Test 1
        $aDeliveryLimitations = array(
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Hour',
                'comparison'     => '==',
                'data'           => '1,7,18,23',
                'executionorder' => 0
            )
        );
        $oDeliveryLimitationManager = new MAX_Maintenance_Priority_DeliveryLimitation($aDeliveryLimitations);
        $result = $oDeliveryLimitationManager->getBlockedOperationIntervalCount($oNowDate, $oPlacementEndDate);
        $this->assertEqual($result, 54);

        // Test 2
        $aDeliveryLimitations = array(
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Hour',
                'comparison'     => '!=',
                'data'           => '1,7,18,23',
                'executionorder' => 0
            )
        );
        $oDeliveryLimitationManager = new MAX_Maintenance_Priority_DeliveryLimitation($aDeliveryLimitations);
        $result = $oDeliveryLimitationManager->getBlockedOperationIntervalCount($oNowDate, $oPlacementEndDate);
        $this->assertEqual($result, 11);

        TestEnv::restoreConfig();
    }

    /**
     * A method to test the getActiveAdOperationIntervals() method.
     *
     * Test 1: Test with an equal blocking limitation.
     * Test 2: Test with a non-equal blocking limitation.
     */
    function testGetActiveAdOperationIntervals()
    {
        $conf = &$GLOBALS['_MAX']['CONF'];
        $conf['maintenance']['operationInterval'] = 60;
        $oNowDate = new Date('2006-02-08 07:05:00');
        $oPlacementEndDate = new Date('2006-02-10');

        // Test 1
        $aDeliveryLimitations = array(
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Hour',
                'comparison'     => '==',
                'data'           => '1,7,18,23',
                'executionorder' => 0
            )
        );
        $oDeliveryLimitationManager = new MAX_Maintenance_Priority_DeliveryLimitation($aDeliveryLimitations);
        $result = $oDeliveryLimitationManager->getActiveAdOperationIntervals(65, $oNowDate, $oPlacementEndDate);
        $this->assertEqual($result, 11);

        // Test 2
        $aDeliveryLimitations = array(
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Hour',
                'comparison'     => '!=',
                'data'           => '1,7,18,23',
                'executionorder' => 0
            )
        );
        $oDeliveryLimitationManager = new MAX_Maintenance_Priority_DeliveryLimitation($aDeliveryLimitations);
        $result = $oDeliveryLimitationManager->getActiveAdOperationIntervals(65, $oNowDate, $oPlacementEndDate);
        $this->assertEqual($result, 54);

        TestEnv::restoreConfig();
    }

    /**
     * A method to test the getAdvertisementLifeData() method.
     *
     * Test 1: Test with invalid parameters, and ensure an empty array is returned.
     * Test 2: Test with equal start and end dates, and ensure an array with just
     *         this operation interval's data is returned.
     * Test 3: Test with a small range of dates in one week, and ensure that an array
     *         for each operation interval is returned.
     * Test 4: Test with a small range of dates over two weeks, and ensure that an
     *         array for each operation interval is returned.
     * Test 5: Test with a multi-week range, a cumulative zone forecast, and a set
     *         of limitations, and ensure that the correct results are returned.
     */
    function testGetAdvertisementLifeData()
    {
        $conf = &$GLOBALS['_MAX']['CONF'];
        $conf['maintenance']['operationInterval'] = 60;

        $aDeliveryLimitations = array();
        $oDeliveryLimitationManager = new MAX_Maintenance_Priority_DeliveryLimitation($aDeliveryLimitations);

        // Test 1
        $oDate = new Date();
        $aCumulativeZoneForecast = array();
        $result = $oDeliveryLimitationManager->getAdvertisementLifeData('foo', $oDate, $aCumulativeZoneForecast);
        $this->assertTrue(is_array($result));
        $this->assertEqual(count($result), 0);
        $result = $oDeliveryLimitationManager->getAdvertisementLifeData($oDate, 'foo', $aCumulativeZoneForecast);
        $this->assertTrue(is_array($result));
        $this->assertEqual(count($result), 0);
        $result = $oDeliveryLimitationManager->getAdvertisementLifeData($oDate, $oDate, 'foo');
        $this->assertTrue(is_array($result));
        $this->assertEqual(count($result), 0);

        // Test 2
        $oDate = new Date();
        $aCumulativeZoneForecast = array();
        $result = $oDeliveryLimitationManager->getAdvertisementLifeData($oDate, $oDate, $aCumulativeZoneForecast);
        $this->assertTrue(is_array($result));
        $this->assertEqual(count($result), 1);
        $this->assertTrue(is_array($result[0]));
        $this->assertEqual(count($result[0]), 1);
        $operationIntervalID = MAX_OperationInterval::convertDateToOperationIntervalID($oDate);
        $this->assertTrue(is_array($result[0][$operationIntervalID]));
        $this->assertEqual(count($result[0][$operationIntervalID]), 2);
        $this->assertNull($result[0][$operationIntervalID]['forecast_impressions']);
        $this->assertFalse($result[0][$operationIntervalID]['blocked']);

        // Test 3
        $oStartDate = new Date('2006-02-15 11:07:15');
        $oEndDate = new Date('2006-02-15 15:59:59');
        $aCumulativeZoneForecast = array();
        $result = $oDeliveryLimitationManager->getAdvertisementLifeData($oStartDate, $oEndDate, $aCumulativeZoneForecast);
        $this->assertTrue(is_array($result));
        $this->assertEqual(count($result), 1);
        $this->assertTrue(is_array($result[0]));
        $this->assertEqual(count($result[0]), 5);
        $operationIntervalID = MAX_OperationInterval::convertDateToOperationIntervalID(new Date('2006-02-15 11:00:01'));
        $this->assertTrue(is_array($result[0][$operationIntervalID]));
        $this->assertEqual(count($result[0][$operationIntervalID]), 2);
        $this->assertNull($result[0][$operationIntervalID]['forecast_impressions']);
        $this->assertFalse($result[0][$operationIntervalID]['blocked']);
        $operationIntervalID = MAX_OperationInterval::convertDateToOperationIntervalID(new Date('2006-02-15 12:00:01'));
        $this->assertTrue(is_array($result[0][$operationIntervalID]));
        $this->assertEqual(count($result[0][$operationIntervalID]), 2);
        $this->assertNull($result[0][$operationIntervalID]['forecast_impressions']);
        $this->assertFalse($result[0][$operationIntervalID]['blocked']);
        $operationIntervalID = MAX_OperationInterval::convertDateToOperationIntervalID(new Date('2006-02-15 13:00:01'));
        $this->assertTrue(is_array($result[0][$operationIntervalID]));
        $this->assertEqual(count($result[0][$operationIntervalID]), 2);
        $this->assertNull($result[0][$operationIntervalID]['forecast_impressions']);
        $this->assertFalse($result[0][$operationIntervalID]['blocked']);
        $operationIntervalID = MAX_OperationInterval::convertDateToOperationIntervalID(new Date('2006-02-15 14:00:01'));
        $this->assertTrue(is_array($result[0][$operationIntervalID]));
        $this->assertEqual(count($result[0][$operationIntervalID]), 2);
        $this->assertNull($result[0][$operationIntervalID]['forecast_impressions']);
        $this->assertFalse($result[0][$operationIntervalID]['blocked']);
        $operationIntervalID = MAX_OperationInterval::convertDateToOperationIntervalID(new Date('2006-02-15 15:00:01'));
        $this->assertTrue(is_array($result[0][$operationIntervalID]));
        $this->assertEqual(count($result[0][$operationIntervalID]), 2);
        $this->assertNull($result[0][$operationIntervalID]['forecast_impressions']);
        $this->assertFalse($result[0][$operationIntervalID]['blocked']);

        // Test 4
        $oStartDate = new Date('2006-02-18 22:07:15');
        $oEndDate = new Date('2006-02-19 02:59:59');
        $aCumulativeZoneForecast = array();
        $result = $oDeliveryLimitationManager->getAdvertisementLifeData($oStartDate, $oEndDate, $aCumulativeZoneForecast);
        $this->assertTrue(is_array($result));
        $this->assertEqual(count($result), 2);
        $this->assertTrue(is_array($result[0]));
        $this->assertEqual(count($result[0]), 2);
        $this->assertTrue(is_array($result[1]));
        $this->assertEqual(count($result[1]), 3);
        $operationIntervalID = MAX_OperationInterval::convertDateToOperationIntervalID(new Date('2006-02-18 22:00:01'));
        $this->assertTrue(is_array($result[0][$operationIntervalID]));
        $this->assertEqual(count($result[0][$operationIntervalID]), 2);
        $this->assertNull($result[0][$operationIntervalID]['forecast_impressions']);
        $this->assertFalse($result[0][$operationIntervalID]['blocked']);
        $operationIntervalID = MAX_OperationInterval::convertDateToOperationIntervalID(new Date('2006-02-18 23:00:01'));
        $this->assertTrue(is_array($result[0][$operationIntervalID]));
        $this->assertEqual(count($result[0][$operationIntervalID]), 2);
        $this->assertNull($result[0][$operationIntervalID]['forecast_impressions']);
        $this->assertFalse($result[0][$operationIntervalID]['blocked']);
        $operationIntervalID = MAX_OperationInterval::convertDateToOperationIntervalID(new Date('2006-02-19 00:00:01'));
        $this->assertTrue(is_array($result[1][$operationIntervalID]));
        $this->assertEqual(count($result[1][$operationIntervalID]), 2);
        $this->assertNull($result[1][$operationIntervalID]['forecast_impressions']);
        $this->assertFalse($result[1][$operationIntervalID]['blocked']);
        $operationIntervalID = MAX_OperationInterval::convertDateToOperationIntervalID(new Date('2006-02-19 01:00:01'));
        $this->assertTrue(is_array($result[1][$operationIntervalID]));
        $this->assertEqual(count($result[1][$operationIntervalID]), 2);
        $this->assertNull($result[1][$operationIntervalID]['forecast_impressions']);
        $this->assertFalse($result[1][$operationIntervalID]['blocked']);
        $operationIntervalID = MAX_OperationInterval::convertDateToOperationIntervalID(new Date('2006-02-19 02:00:01'));
        $this->assertTrue(is_array($result[1][$operationIntervalID]));
        $this->assertEqual(count($result[1][$operationIntervalID]), 2);
        $this->assertNull($result[1][$operationIntervalID]['forecast_impressions']);
        $this->assertFalse($result[1][$operationIntervalID]['blocked']);

        // Test 5
        $aDeliveryLimitations = array(
            array(
                'ad_id'          => 1,
                'logical'        => 'and',
                'type'           => 'Time:Date',
                'comparison'     => '!=',
                'data'           => '2006-02-26',
                'executionorder' => 0
            )
        );
        $oDeliveryLimitationManager = new MAX_Maintenance_Priority_DeliveryLimitation($aDeliveryLimitations);
        $oStartDate = new Date('2006-02-07 12:07:15');
        $oEndDate = new Date('2006-02-27 23:59:59');
        $aCumulativeZoneForecast = array(
            12 => 57,
            80 => 22
        );
        $result = $oDeliveryLimitationManager->getAdvertisementLifeData($oStartDate, $oEndDate, $aCumulativeZoneForecast);
        $this->assertTrue(is_array($result));
        $this->assertEqual(count($result), 4);
        $this->assertTrue(is_array($result[0]));
        $this->assertEqual(count($result[0]), 12 + (4 * 24));
        $this->assertTrue(is_array($result[1]));
        $this->assertEqual(count($result[1]), (7 * 24));
        $this->assertTrue(is_array($result[2]));
        $this->assertEqual(count($result[2]), (7 * 24));
        $this->assertTrue(is_array($result[3]));
        $this->assertEqual(count($result[3]), (2 * 24));
        $oTestDateStart = new Date('2006-02-07 12:00:01');
        $oTestDateEnd   = new Date('2006-02-11 23:00:01');
        while (!$oTestDateStart->after($oTestDateEnd)) {
            $operationIntervalID = MAX_OperationInterval::convertDateToOperationIntervalID($oTestDateStart);
            $this->assertTrue(is_array($result[0][$operationIntervalID]));
            $this->assertEqual(count($result[0][$operationIntervalID]), 2);
            if ($operationIntervalID == 12) {
                $this->assertEqual($result[0][$operationIntervalID]['forecast_impressions'], 57);
            } elseif ($operationIntervalID == 80) {
                $this->assertEqual($result[0][$operationIntervalID]['forecast_impressions'], 22);
            } else {
                $this->assertNull($result[0][$operationIntervalID]['forecast_impressions']);
            }
            $this->assertFalse($result[0][$operationIntervalID]['blocked']);
            $oTestDateStart->addSeconds(MAX_OperationInterval::secondsPerOperationInterval());
        }
        $oTestDateStart = new Date('2006-02-12 00:00:01');
        $oTestDateEnd   = new Date('2006-02-18 23:00:01');
        while (!$oTestDateStart->after($oTestDateEnd)) {
            $operationIntervalID = MAX_OperationInterval::convertDateToOperationIntervalID($oTestDateStart);
            $this->assertTrue(is_array($result[1][$operationIntervalID]));
            $this->assertEqual(count($result[1][$operationIntervalID]), 2);
            if ($operationIntervalID == 12) {
                $this->assertEqual($result[1][$operationIntervalID]['forecast_impressions'], 57);
            } elseif ($operationIntervalID == 80) {
                $this->assertEqual($result[1][$operationIntervalID]['forecast_impressions'], 22);
            } else {
                $this->assertNull($result[1][$operationIntervalID]['forecast_impressions']);
            }
            $this->assertFalse($result[1][$operationIntervalID]['blocked']);
            $oTestDateStart->addSeconds(MAX_OperationInterval::secondsPerOperationInterval());
        }
        $oTestDateStart = new Date('2006-02-19 00:00:01');
        $oTestDateEnd   = new Date('2006-02-25 23:00:01');
        while (!$oTestDateStart->after($oTestDateEnd)) {
            $operationIntervalID = MAX_OperationInterval::convertDateToOperationIntervalID($oTestDateStart);
            $this->assertTrue(is_array($result[2][$operationIntervalID]));
            $this->assertEqual(count($result[2][$operationIntervalID]), 2);
            if ($operationIntervalID == 12) {
                $this->assertEqual($result[2][$operationIntervalID]['forecast_impressions'], 57);
            } elseif ($operationIntervalID == 80) {
                $this->assertEqual($result[2][$operationIntervalID]['forecast_impressions'], 22);
            } else {
                $this->assertNull($result[2][$operationIntervalID]['forecast_impressions']);
            }
            $this->assertFalse($result[2][$operationIntervalID]['blocked']);
            $oTestDateStart->addSeconds(MAX_OperationInterval::secondsPerOperationInterval());
        }
        $oTestDateStart = new Date('2006-02-26 00:00:01');
        $oTestDateEnd   = new Date('2006-02-26 23:00:01');
        while (!$oTestDateStart->after($oTestDateEnd)) {
            $operationIntervalID = MAX_OperationInterval::convertDateToOperationIntervalID($oTestDateStart);
            $this->assertTrue(is_array($result[3][$operationIntervalID]));
            $this->assertEqual(count($result[3][$operationIntervalID]), 2);
            if ($operationIntervalID == 12) {
                $this->assertEqual($result[3][$operationIntervalID]['forecast_impressions'], 57);
            } elseif ($operationIntervalID == 80) {
                $this->assertEqual($result[3][$operationIntervalID]['forecast_impressions'], 22);
            } else {
                $this->assertNull($result[3][$operationIntervalID]['forecast_impressions']);
            }
            $this->assertTrue($result[3][$operationIntervalID]['blocked']);
            $oTestDateStart->addSeconds(MAX_OperationInterval::secondsPerOperationInterval());
        }
        $oTestDateStart = new Date('2006-02-27 00:00:01');
        $oTestDateEnd   = new Date('2006-02-27 23:00:01');
        while (!$oTestDateStart->after($oTestDateEnd)) {
            $operationIntervalID = MAX_OperationInterval::convertDateToOperationIntervalID($oTestDateStart);
            $this->assertTrue(is_array($result[3][$operationIntervalID]));
            $this->assertEqual(count($result[3][$operationIntervalID]), 2);
            if ($operationIntervalID == 12) {
                $this->assertEqual($result[3][$operationIntervalID]['forecast_impressions'], 57);
            } elseif ($operationIntervalID == 80) {
                $this->assertEqual($result[3][$operationIntervalID]['forecast_impressions'], 22);
            } else {
                $this->assertNull($result[3][$operationIntervalID]['forecast_impressions']);
            }
            $this->assertFalse($result[3][$operationIntervalID]['blocked']);
            $oTestDateStart->addSeconds(MAX_OperationInterval::secondsPerOperationInterval());
        }

        TestEnv::restoreConfig();
    }

}

?>
