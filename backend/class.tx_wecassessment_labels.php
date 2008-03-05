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

require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_assessment.php');
require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_answer.php');
require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_category.php');

/**
 * General purpose class for providing record labels.
 *
 * @author	Web-Empowered Church Team <assessment@webempoweredchurch.org>
 * @package TYPO3
 * @subpackage tx_wecassessment
 */
class tx_wecassessment_labels {
	
	/**
	 * Gets the label for a category record.
	 *
	 * @param		array		Params array, passed by reference. $params['title'] is set to the new label.
	 * @param		object		Parent object.
	 * @return		none
	 * @todo 		Localize!
	 */
	function getCategoryLabel(&$params, &$pObj) {
		$uid = $params['row']['uid'];
		if(is_numeric($uid)) {
			$category = &tx_wecassessment_category::find($uid, true);
			if(is_object($category)) {
				$params['title'] = $category->getLabel();
			}
		} else {
			$params['title'] = '[ New Category ]';
		}
		
	}
	
	/**
	 * Gets the label for a question record.
	 *
	 * @param		array		Params array, passed by reference. $params['title] is set to the new label.
	 * @param		object		Parent object.
	 * @return		none
	 * @todo 		Localize!
	 */
	function getQuestionLabel(&$params, &$pObj) {
		if($pObj == null) {
			$isInline = true;
		}
		
		$uid = $params['row']['uid'];
		if(is_numeric($uid)) {
			$question = &tx_wecassessment_question::find($uid, true);
			if(is_object($question)) {
				$titleLength = $GLOBALS['BE_USER']->uc['titleLen'];
				$params['title'] = t3lib_div::fixed_lgd_cs(strip_tags($question->getLabel($isInline)), $titleLength);
			}
		} else {
			$params['title'] = '[ New Question ]';
		}
	}
		
	
	/**
	 * Gets the label for a result record.
	 *
	 * @param		array		Params array, passed by reference.  $params['title'] is set to the new label.
	 * @param		object		Parent object.
	 * @return		none
	 * @todo 		Localize!
	 */
	function getResultLabel(&$params, &$pObj) {
		$uid = $params['row']['uid'];
		if(is_numeric($uid)) {
			$result = &tx_wecassessment_result::find($uid, true);
			$params['title'] = $result->getLabel();
		} else {
			$params['title'] = '[ New Result ]';
		}
	}
	
	/**
	 * Gets the label for a recommendation record.
	 *
	 * @param		array		Params array, passed by reference.  $params['title'] is set to the new label.
	 * @param		object		Parent object.
	 * @return		none
	 * @todo 		Localize!
	 */
	function getRecommendationLabel(&$params, &$pObj) {
		if($pObj == null) {
			$isInline = true;
		}
		
		$uid = $params['row']['uid'];
		if(is_numeric($uid)) {
			$recommendation = &tx_wecassessment_recommendation::find($uid, true);
			$params['title'] = $recommendation->getLabel($isInline);
		} else {
			$params['title'] = '[ New Recommendation ]';
		}
	}
	
	/**
	 * Gets the label for an answer record.
	 *
	 * @param		array		Params array, passed by reference.  $params['title'] is set to the new label.
	 * @param		object		Parent object.
	 * @return		none
	 * @todo 		Localize!
	 */
	function getAnswerLabel(&$params, &$pObj) {
		$uid = $params['row']['uid'];
		if(is_numeric($uid)) {
			$answer = &tx_wecassessment_answer::find($uid, true);
			$params['title'] = $answer->getLabel();
		} else {
			$params['title'] = '[ New Answer ]';
		}
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/backend/class.tx_wecassessment_labels.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/backend/class.tx_wecassessment_labels.php']);
}  
  
?>
