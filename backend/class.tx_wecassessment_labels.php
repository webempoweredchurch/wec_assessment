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

require_once(t3lib_extMgm::extPath('wec_assessment') . 'model/class.tx_wecassessment_assessment.php');
require_once(t3lib_extMgm::extPath('wec_assessment') . 'model/class.tx_wecassessment_answer.php');
require_once(t3lib_extMgm::extPath('wec_assessment') . 'model/class.tx_wecassessment_category.php');

/**
 * General purpose class for providing record labels.
 *
 * @author	Web-Empowered Church Team <assessment@webempoweredchurch.org>
 * @package TYPO3
 * @subpackage tx_wecassessment
 */
class tx_wecassessment_labels {
	
	/**
	 * Constructor
	 *
	 * @return void
	 **/
	function tx_wecassessment_labels() {
		$this->__construct();
	}
	
	/**
	 * Constructore, sets up the Locallang stuff
	 *
	 * @return void
	 **/
	function __construct() {
		if(!is_object($GLOBALS['LANG'])) {
			require_once(t3lib_extMgm::extPath('lang') . 'lang.php');
			$GLOBALS['LANG'] = t3lib_div::makeInstance('language');
			
			if(TYPO3_MODE == 'BE') {
				$GLOBALS['LANG']->init($BE_USER->uc['lang']);
			} else {
				$GLOBALS['LANG']->init($GLOBALS['TSFE']->config['config']['language']);
			}
		}
		
		$GLOBALS['LANG']->includeLLFile('EXT:wec_assessment/locallang.xml');
	}
	
	/**
	 * Gets the label for a category record.
	 *
	 * @param		array		Params array, passed by reference. $params['title'] is set to the new label.
	 * @param		object		Parent object.
	 * @return		none
	 */
	function getCategoryLabel(&$params, &$pObj) {
		$uid = $params['row']['uid'];
		if(is_numeric($uid)) {
			$category = &tx_wecassessment_category::find($uid, true);
			if(is_object($category)) {
				$params['title'] = $category->getLabel();
			}
		} else {
			$params['title'] = $GLOBALS['LANG']->getLL('new_category');
		}
		
	}
	
	/**
	 * Gets the label for a question record.
	 *
	 * @param		array		Params array, passed by reference. $params['title] is set to the new label.
	 * @param		object		Parent object.
	 * @return		none
	 */
	function getQuestionLabel(&$params, &$pObj) {
		$isInline = $this->isInlineEditing($params, $pObj);
		
		$uid = $params['row']['uid'];
		if(is_numeric($uid)) {
			$question = &tx_wecassessment_question::find($uid, true);
			if(is_object($question)) {
				$titleLength = $GLOBALS['BE_USER']->uc['titleLen'];
				$params['title'] = t3lib_div::fixed_lgd_cs(strip_tags($question->getLabel($isInline)), $titleLength);
			}
		} else {
			$params['title'] = $GLOBALS['LANG']->getLL('new_question');
		}
	}
		
	
	/**
	 * Gets the label for a result record.
	 *
	 * @param		array		Params array, passed by reference.  $params['title'] is set to the new label.
	 * @param		object		Parent object.
	 * @return		none
	 */
	function getResultLabel(&$params, &$pObj) {
		$uid = $params['row']['uid'];
		if(is_numeric($uid)) {
			$result = &tx_wecassessment_result::find($uid, true);
			$params['title'] = $result->getLabel();
		} else {
			$params['title'] = $GLOBALS['LANG']->getLL('new_result');
		}
	}
	
	/**
	 * Gets the label for a recommendation record.
	 *
	 * @param		array		Params array, passed by reference.  $params['title'] is set to the new label.
	 * @param		object		Parent object.
	 * @return		none
	 */
	function getRecommendationLabel(&$params, &$pObj) {
		$isInline = $this->isInlineEditing($params, $pObj);
		
		$uid = $params['row']['uid'];
		if(is_numeric($uid)) {
			$recommendation = &tx_wecassessment_recommendation::find($uid, true);
			$params['title'] = $recommendation->getLabel($isInline);
		} else {
			$params['title'] = $GLOBALS['LANG']->getLL('new_recommendation');
		}
	}
	
	/**
	 * Gets the label for an answer record.
	 *
	 * @param		array		Params array, passed by reference.  $params['title'] is set to the new label.
	 * @param		object		Parent object.
	 * @return		none
	 */
	function getAnswerLabel(&$params, &$pObj) {
		$uid = $params['row']['uid'];
		if(is_numeric($uid)) {
			$answer = &tx_wecassessment_answer::find($uid, true);
			$params['title'] = $answer->getLabel();
		} else {
			$params['title'] = $GLOBALS['LANG']->getLL('new_answer');
		}
	}
	
	/**
	 * Determines if the label userFunc is running as part of IRRE.  If so, a
	 * shorter title with less context can be used.
	 *
	 * @param		array		Array of parameters, included database row.
	 * @param		object		Parent object.
	 * @return		none
	 */
	function isInlineEditing($params, $pObj) {
		if(array_key_exists('deleted', $params['row']) && array_key_exists('hidden', $params['row'])) {
			$isInlineEditing = true;
		} else {
			$isInlineEditing = false;
		}
		
		return $isInlineEditing;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/backend/class.tx_wecassessment_labels.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/backend/class.tx_wecassessment_labels.php']);
}
?>
