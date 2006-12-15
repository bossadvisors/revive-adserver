<?php

/*
+---------------------------------------------------------------------------+
| Max Media Manager v0.3                                                    |
| =================                                                         |
|                                                                           |
| Copyright (c) 2003-2006 m3 Media Services Limited                         |
| For contact details, see: http://www.m3.net/                              |
|                                                                           |
| Copyright (c) 2000-2003 the phpAdsNew developers                          |
| For contact details, see: http://www.phpadsnew.com/                       |
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

require_once MAX_PATH . '/lib/max/other/lib-acl.inc.php';

/*
 * A class for testing the lib-geometry.
 *
 * @package    MaxPlugin
 * @subpackage TestSuite
 * @author     Andrzej Swedrzynski <andrzej.swedrzynski@m3.net>
 */
class LibAclTest extends UnitTestCase
{
     /**
     * The constructor method.
     */
    function LibAclTest()
    {
        $this->UnitTestCase();
    }
    
    
    function testMAX_aclAStripslashed()
    {
        set_magic_quotes_runtime(0);
        $aValue = array('aabb', 'aa\\\\bb', 'aa\\\'bb');
        $aExpected = array('aabb', 'aa\\bb', 'aa\'bb');
        $aActual = MAX_aclAStripslashed($aValue);
        $this->assertEqual($aExpected, $aActual);
        
        $aValue = array('aabb', 'aa\\\\bb', array('aa\\\'bb', 'cc\\\\dd'));
        $aExpected = array('aabb', 'aa\\bb', array('aa\'bb', 'cc\\dd'));
        $aActual = MAX_aclAStripslashed($aValue);
        $this->assertEqual($aExpected, $aActual);

        set_magic_quotes_runtime(1);
        $aValue = array('aabb', 'aa\\\\bb', 'aa\\\'bb');
        $aExpected = $aValue;
        $aActual = MAX_aclAStripslashed($aValue);
        $this->assertEqual($aExpected, $aActual);
    }
}
?>