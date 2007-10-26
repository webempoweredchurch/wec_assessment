<?php

/***************************************************************
* Copyright notice
*
* (c) 2007 Foundation for Evangelism
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

require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_recommendation.php');

class tx_wecassessment_tcemain_processdatamap {
	
	function processDatamap_preProcessFieldArray(&$incomingFieldArray, $table, $id, &$pObj) {
		/* If we're saving a question from within a category, don't resort */
		if($table == 'tx_wecassessment_category') {		
			unset($GLOBALS['TCA']['tx_wecassessment_question']['ctrl']['sortby']);
		}
	}
	
	function processDatamap_postProcessFieldArray($status, $table, $id, &$fieldArray, &$pObj) {
		if($table == 'tx_wecassessment_recommendation') {
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


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/backend/class.tx_wecassessment_tcemain_processdatamap.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/backend/class.tx_wecassessment_tcemain_processdatamap.php']);
}

?>