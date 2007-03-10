<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2007 Web-Empowered Church Team
 *  Contact: info@webempoweredchurch.org
 *  All rights reserved
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 ***************************************************************/

$key = 'wec_assessment';   // extension key
$class = 'exampleTest'; // class name

// Fix part to set after class definition
if(!defined('PATH_site')) { // If running from command line

	// Setup environment
	$path = $_SERVER['PWD'] .'/'. $_SERVER['SCRIPT_NAME'];
	
	if(!preg_match('|(.*)(typo3conf.*)(' . $key . '/tests)|', $path, $matches)) {
		if(!preg_match('|(.*)(typo3/sysext.*)(' . $key . '/tests)|', $path, $matches))
			exit(chr(10) . 'Unknown installation path' . chr(10). $path . chr(10));
    }
 
    define('PATH_site', $matches[1]);
    $GLOBALS['TYPO3_LOADED_EXT'][$key]['siteRelPath']= $matches[2] . $key . '/';
    define('PATH_t3lib', PATH_site . 't3lib/');
    require_once(PATH_t3lib . 'class.t3lib_div.php');
    require_once(PATH_t3lib . 'class.t3lib_extmgm.php');
	define('PATH_tslib', PATH_site.'typo3/sysext/cms/tslib/');
	#define('PATH_tslib', '/var/www/typo3/sysext/cms/tslib/');
}


?>
