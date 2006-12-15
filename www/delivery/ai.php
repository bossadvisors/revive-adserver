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

// Require the initialisation file
require_once '../../init-delivery.php';

// Required files
require_once MAX_PATH . '/lib/max/Admin/Preferences.php';
require_once MAX_PATH . '/lib/max/Delivery/cache.php';

//Register any script specific input variables
MAX_commonRegisterGlobals('filename', 'contenttype');

if (isset($filename) && $filename != '') {
    $aCreative = MAX_cacheGetCreative($filename);

	if (empty($aCreative)) {
		// Filename not found, show the admin user's default banner
		// (as the agency cannot be determined from a filename)
		$pref = MAX_Admin_Preferences::loadPrefs(0);
		if ($pref['default_banner_url'] != "") {
			Header("Location: ".$pref['default_banner_url']);
		}
	} else {
		// Filename found, dump contents to browser
		// Check if the browser sent a If-Modified-Since header and if the image was
		// modified since that date
		if (!isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ||
			$aCreative['t_stamp'] > strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
			header("Last-Modified: ".gmdate('D, d M Y H:i:s', $aCreative['t_stamp']).' GMT');
			if (isset($contenttype) && $contenttype != '') {
				switch ($contenttype) {
					case 'swf': header('Content-type: application/x-shockwave-flash; name='.$filename); break;
					case 'dcr': header('Content-type: application/x-director; name='.$filename); break;
					case 'rpm': header('Content-type: audio/x-pn-realaudio-plugin; name='.$filename); break;
					case 'mov': header('Content-type: video/quicktime; name='.$filename); break;
					default:	header('Content-type: image/'.$contenttype.'; name='.$filename); break;
				}
			}
			echo $aCreative['contents'];
		} else {
			// Send "Not Modified" status header
			if (php_sapi_name() == 'cgi') {
				// PHP as CGI, use Status: [status-number]
				header('Status: 304 Not Modified');
			} else {
				// PHP as module, use HTTP/1.x [status-number]
				header($_SERVER['SERVER_PROTOCOL'].' 304 Not Modified');
			}
		}
	}
} else {
	// Filename not specified, show the admin user's default banner
	// (as the agency cannot be determined from a filename)
	$pref = MAX_Admin_Preferences::loadPrefs(0);
	if ($pref['default_banner_url'] != "") {
		header("Location: ".$pref['default_banner_url']);
	}
}

// stop benchmarking
MAX_benchmarkStop();

?>