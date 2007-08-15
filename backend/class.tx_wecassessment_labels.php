<?php

require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_assessment.php');

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
	}
	
	/**
	 * Gets the label for a response record.
	 *
	 * @param		array		Params array, passed by reference.  $params['title'] is set to the new label.
	 * @param		object		Parent object.
	 * @return		none
	 */
	function getResponseLabel(&$params, &$pObj) {
		$uid = $params['row']['uid'];
		if(is_numeric($uid)) {
			$response = &tx_wecassessment_response::find($uid, true);
			$params['title'] = $response->getLabel();
		} else {
			$params['title'] = '[ New Response ]';
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
			$params['title'] = '[ New Answer ]';
		}
	}
	
	function getAnswerOptions($config) {
		$assessmentClass = t3lib_div::makeInstanceClassName('tx_wecassessment_assessment');
		$assessment = new $assessmentClass(0, $config['row']['pid']);
		$answerSet = &$assessment->getAnswerSet();
		
		foreach($answerSet as $value => $label) {
			$config['items'][] = Array($label, $value);
		}
		
		return $config;
	}
		
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/backend/class.tx_wecassessment_labels.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/backend/class.tx_wecassessment_labels.php']);
}  
  
?>
