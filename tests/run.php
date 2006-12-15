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
 * A script to call the TestRunner class, based on the $_GET parameters
 * passed via the web client, as well as perform timing of the tests,
 * etc.
 *
 * @package    Max
 * @subpackage TestSuite
 * @author     Andrew Hill <andrew@m3.net>
 * 
 * @todo Only show HTML when run from Web.  
 */

require_once 'init.php';

// Required files
require_once MAX_PATH . '/tests/testClasses/TestRunner.php';

$runner = new TestRunner();
$runner->findDefaults();


/* TODO: Extract this to the paintHeader() method of a reporter */
if ($runner->output_format_name == 'html') {
    echo "<style type=\"text/css\">\n";
    echo file_get_contents(MAX_PATH . '/tests/testClasses/tests.css');
    echo "</style>\n";
}

/* TODO: Consider a non-Web environment */
if (defined('TEST_ENVIRONMENT_NO_CONFIG')) {
    echo "<h1>Cannot Run Tests</h1>\n";
    echo "<p>You have not copied the the test.conf.ini file in the\n";
    echo "/etc directory into the /var directory, and edited the file,\n";
    echo "so that it contains your database server details.</p>\n";
    exit();
}

$start = microtime();

// Store the type of test being run globally, to save passing
// about as a parameter all the time
$GLOBALS['_MAX']['TEST']['test_type'] = $_GET['type'];

// Set longer time out
if (!ini_get('safe_mode')) {
    $conf = $GLOBALS['_MAX']['CONF'];
    @set_time_limit($conf['maintenance']['timeLimitScripts']);
}

$level = $_GET['level'];
if ($level == 'all') {
    $runner->runAll();
} elseif ($level == 'layer') {
    $layer = $_GET['layer'];
    $runner->runLayer($layer);
} elseif ($level == 'folder') {
    $layer = $_GET['layer'];
    $folder = $_GET['folder'];
    $runner->runFolder($layer, $folder);
} elseif ($level == 'file') {
    $layer = $_GET['layer'];
    $folder = $_GET['folder'];
    $file = $_GET['file'];
    $runner->runFile($layer, $folder, $file);
}


// Display execution time
list ($endUsec, $endSec) = explode(" ", microtime());
$endTime = ((float) $endUsec + (float) $endSec);
list ($startUsec, $startSec) = explode(" ", $start);
$startTime = ((float) $startUsec + (float) $startSec);

/* TODO: Extract this to the paintFooter() method of a reporter */
if ($runner->output_format_name == 'html') {
    echo '<div align="right"><br/>Test Suite Execution Time ~ <b>';
    echo substr(($endTime - $startTime), 0, 6);
    echo '</b> seconds.</div>';
}
$runner->exitWithCode();
?>
