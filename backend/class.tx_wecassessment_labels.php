<?php

require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_assessment.php');
require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_answer.php');
require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_category.php');

class tx_wecassessment_labels {
	
	
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
			$params['title'] = '[ New Category ]';
		}
		
		$params['title'] = strip_tags($params['title']);
	}
	
	/**
	 * Gets the label for a question record.
	 *
	 * @param		array		Params array, passed by reference. $params['title] is set to the new label.
	 * @param		object		Parent object.
	 * @return		none
	 */
	function getQuestionLabel(&$params, &$pObj) {
		$uid = $params['row']['uid'];
		if(is_numeric($uid)) {
			$question = &tx_wecassessment_question::find($uid, true);
			if(is_object($question)) {
				$titleLength = $GLOBALS['BE_USER']->uc['titleLen'];
				$params['title'] = htmlspecialchars(t3lib_div::fixed_lgd_cs($question->getLabel(), $titleLength));
			}
		} else {
			$params['title'] = '[ New Question ]';
		}
		
		$params['title'] = strip_tags($params['title']);
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
			$params['title'] = '[ New Result ]';
		}
		
		$params['title'] = strip_tags($params['title']);
	}
	
	/**
	 * Gets the label for a recommendation record.
	 *
	 * @param		array		Params array, passed by reference.  $params['title'] is set to the new label.
	 * @param		object		Parent object.
	 * @return		none
	 */
	function getRecommendationLabel(&$params, &$pObj) {
		$uid = $params['row']['uid'];
		if(is_numeric($uid)) {
			$recommendation = &tx_wecassessment_recommendation::find($uid, true);
			$params['title'] = $recommendation->getLabel();
		} else {
			$params['title'] = '[ New Recommendation ]';
		}
		
		$params['title'] = strip_tags($params['title']);
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
			$params['title'] = '[ New Answer ]';
		}
		
		$params['title'] = strip_tags($params['title']);
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/backend/class.tx_wecassessment_labels.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/backend/class.tx_wecassessment_labels.php']);
}  
  
?>
