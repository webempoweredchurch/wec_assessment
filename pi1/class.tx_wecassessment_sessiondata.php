<?php

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