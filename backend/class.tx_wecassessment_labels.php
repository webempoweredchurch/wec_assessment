<?php

class tx_wecassessment_labels {
	
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
			$result = tx_wecassessment_result::find($uid);
			$params['title'] = $result->getUsername();
		} else {
			$params['title'] = '[New Result]';
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
			$response = tx_wecassessment_response::find($uid);
			$category = $response->getCategory();
		
			if(is_object($category)) {
				$title = $category->getTitle();
			}
		
			$params['title'] = $title.': '.$response->getMinValue().'-'.$response->getMaxValue();
		} else {
			$params['title'] = '[New Response]';
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
			$answer = tx_wecassessment_answer::find($uid);
			$question = $answer->getQuestion();
			$result = $answer->getResult();		
			
			$params['title'] = $result->getUsername().': '.$question->getText();
		} else {
			$params['title'] = '[New Answer]';
		}
	}
		
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cal/res/class.tx_cal_labels.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cal/res/class.tx_cal_labels.php']);
}  
  
?>
