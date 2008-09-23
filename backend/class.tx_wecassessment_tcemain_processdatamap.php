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

require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_recommendation.php');

/**
 * Class for pre and post processing TCE Main configuration.
 *
 * @author	Web-Empowered Church Team <assessment@webempoweredchurch.org>
 * @package TYPO3
 * @subpackage tx_wecassessment
 */
class tx_wecassessment_tcemain_processdatamap {
	
	/**
	 * Preprocessing of backend form data.  Prevents resorting when questions are saved within IRRE.
	 * @param		array		Array of form data.
	 * @param		string		Name of the table being saved.
	 * @param		mixed		UID of the record being saved. Can be a string for new records.
	 * @param		object		tcemain object.
	 * @return		none
	 */
	function processDatamap_preProcessFieldArray(&$incomingFieldArray, $table, $id, &$pObj) {
		/* If we're saving a question from within a category, don't resort */
		if($table == 'tx_wecassessment_category') {		
			unset($GLOBALS['TCA']['tx_wecassessment_question']['ctrl']['sortby']);
		}
		
		/* If we're changing the type on an existing result record, unset the user ID */
		if(($table == 'tx_wecassessment_result') && !strstr($id, 'NEW')) {
			$result = tx_wecassessment_result::find($id);
			if(is_object($result) && ($result->getType() != $incomingFieldArray['type'])) {
				$incomingFieldArray['feuser_id'] = 0;
			}
		}
	}
	
	/**
	 * Post processing of backend form data.  As the recommenation type is switched,
	 * empty out the values that no longer matter.
	 * @param		string		Status code.
	 * @param		string		Name of the table that was saved.
	 * @param		integer		UID of the record that was saved.
	 * @param		array		Array of form data.
	 * @param		object		tcemain object.
	 * @return		none
	 */
	function processDatamap_postProcessFieldArray($status, $table, $id, &$fieldArray, &$pObj) {
		if($table == 'tx_wecassessment_recommendation') {
			if(array_key_exists('type', $fieldArray)) {
				switch($fieldArray['type']) {
					case TX_WECASSESSMENT_RECOMMENDATION_CATEGORY:
						$fieldArray['question_id'] = 0;
					break;
					case TX_WECASSESSMENT_RECOMMENDATION_QUESTION:
						$fieldArray['category_id'] = 0;
					break;
					case TX_WECASSESSMENT_RECOMMENDATION_ASSESSMENT:
						$fieldArray['question_id'] = 0;
						$fieldArray['category_id'] = 0;
					break;
				}
			}
		}		
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/backend/class.tx_wecassessment_tcemain_processdatamap.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/backend/class.tx_wecassessment_tcemain_processdatamap.php']);
}
?>