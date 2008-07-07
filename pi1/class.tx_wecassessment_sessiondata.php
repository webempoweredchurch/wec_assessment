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

class tx_wecassessment_sessiondata {
	
	/*
	 * Stores session data.
	 * @param		array		The array of session data to store.
	 * @return		none
	 */
	function storeSessionData($sessionData) {
		$GLOBALS["TSFE"]->fe_user->setKey("ses","tx_wecassessment_pi1", $sessionData);
		$GLOBALS["TSFE"]->fe_user->sesData_change = true;
		$GLOBALS["TSFE"]->fe_user->storeSessionData();		
	}
	
	/*
	 * Retrieves session data.
	 * @return		array		The array of session data for the extension.
	 */
	function retrieveSessionData() {
		//return $GLOBALS["TSFE"]->fe_user->getKey("ses","tx_wecassessment_pi1");		
		return tx_wecassessment_sessiondata::fetchSessionData('tx_wecassessment_pi1');
	}
	
	/**
	 * Copied directly from class.tslib_feuserauth.php.  Function must run
	 * here so that properly classes are loaded before we unserialize session
	 * data.
	 * @param		key			The key to retrieve.
	 * @return		mixed		The session data. 
	 *
	 */
	function fetchSessionData($key)	{
		$id = $GLOBALS["TSFE"]->fe_user->id;		
		// Gets SesData if any
		if ($id)	{
			$dbres = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'fe_session_data', 'hash='.$GLOBALS['TYPO3_DB']->fullQuoteStr($id, 'fe_session_data'));
			if ($sesDataRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($dbres))	{
				$sesData = unserialize($sesDataRow['content']);
			}
			$GLOBALS['TYPO3_DB']->sql_free_result($dbres);
		}
			// delete old data:
		if ((rand()%100) <= 1) {		// a possibility of 1 % for garbage collection.
			$GLOBALS['TYPO3_DB']->exec_DELETEquery('fe_session_data', 'tstamp < '.intval(time()-3600*24));		// all data older than 24 hours are deleted.
		}
		
		return $sesData[$key];
	}
	
	
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/pi1/class.tx_wecassessment_sessiondata.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/pi1/class.tx_wecassessment_sessiondata.php']);
}


?>