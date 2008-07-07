<?php
/***************************************************************
* Copyright notice
*
* (c) 2007-2008 Christian Technology Ministries International Inc.
* All rights reserved
*
* This file is part of the Web-Empowered Church (WEC)
* (http://WebEmpoweredChurch.org) ministry of Christian Technology Ministries 
* International (http://CTMIinc.org). The WEC is developing TYPO3-based
* (http://typo3.org) free software for churches around the world. Our desire
* is to use the Internet to help offer new life through Jesus Christ. Please
* see http://WebEmpoweredChurch.org/Jesus.
*
* You can redistribute this file and/or modify it under the terms of the
* GNU General Public License as published by the Free Software Foundation;
* either version 2 of the License, or (at your option) any later version.
*
* The GNU General Public License can be found at
* http://www.gnu.org/copyleft/gpl.html.
*
* This file is distributed in the hope that it will be useful for ministry,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* This copyright notice MUST APPEAR in all copies of the file!
***************************************************************/

if (!defined('PATH_tslib')) define('PATH_tslib', t3lib_extMgm::extPath('cms').'tslib/');

require_once (PATH_tslib.'class.tslib_fe.php');

/**
 * Helper class that extends TSFE to disable page not found handling when creating a fake frontend instance.
 *
 * @author	Web-Empowered Church Team <assessment@webempoweredchurch.org>
 * @package TYPO3
 * @subpackage tx_wecassessment
 */
class tx_wecassessment_tsfe extends tslib_fe {

	/**
	 * Constructor.  Passes all arguments to the real constructor.
	 */
	function tx_wecassessment_tsfe($TYPO3_CONF_VARS, $id, $type, $no_cache='', $cHash='', $jumpurl='',$MP='',$RDCT=''){
		return $this->tslib_fe($TYPO3_CONF_VARS, $id, $type, $no_cache, $cHash, $jumpurl,$MP,$RDCT);
	}
	
	/**
	 * Override for page not found handling.
	 */
	function pageNotFoundHandler($code, $header='', $reason='')	{
		//do nothing
	}

	/**
	 * Override for page not found handling.
	 */	
	function pageNotFoundAndExit($reason='', $header='')	{
		// do nothing
	}
}

?>