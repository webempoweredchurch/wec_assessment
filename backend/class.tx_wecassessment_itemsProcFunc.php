<?php
/***************************************************************
* Copyright notice
*
* (c) 2007 Foundation for Evangelism (info@evangelize.org)
* All rights reserved
*
* This file is part of the Web-Empowered Church (WEC)
* (http://webempoweredchurch.org) ministry of the Foundation for Evangelism
* (http://evangelize.org). The WEC is developing TYPO3-based
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

/**
 * General purpose class for providing TCA lists in the backend.
 *
 * @author	Web-Empowered Church Team <assessment@webempoweredchurch.org>
 * @package TYPO3
 * @subpackage tx_wecassessment
 */
class tx_wecassessment_itemsProcFunc {
	
	/**
	 * Gets the TCA list of sort options.
	 * @param		array		Configuration array.
	 * @return		array		Configuration array.
	 * @todo 		Localize!
	 */
	function getSortItems($config=null) {
		if(!isset($config)) {
			$config = array();
		}
		
		$config['items'][] = Array('Random', 'random');
		
		$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['wec_assessment']);
		if($confArr['manualQuestionSorting']) {
			$config['items'][] = Array('Use backend sorting', 'backend');
		}
		
		return $config;
	}
	
	/**
	 * Gets the answer options from the flexform.
	 * @param		array		Configuration array.
	 * @return		array		Configuration array.
	 */
	function getAnswerOptions($config) {
		$assessmentClass = t3lib_div::makeInstanceClassName('tx_wecassessment_assessment');
		
		/**
		 * @todo 	Empty array is a temporary fix to avoid creating a
		 *			frontend session.  Really need a persistent object here,
		 *			maybe borrowing from the registry in cal.
		 */ 
		$assessment = new $assessmentClass(0, $config['row']['pid'], array());
		$answerSet = &$assessment->getAnswerSet();
		
		foreach((array) $answerSet as $value => $label) {
			$config['items'][] = Array($label, $value);
		}
		
		return $config;
	}
	
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/backend/class.tx_wecassessment_itemsProcFunc.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/class.tx_wecassessment_itemsProcFunc.php']);
}
?>