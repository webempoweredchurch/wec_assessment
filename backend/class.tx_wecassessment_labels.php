<?php

class tx_wecassessment_labels {
	
	function getResultLabel(&$params, &$pObj) {
		$uid = $params['row']['uid'];
		$feuser_id = $params['row']['feuser_id'];
		$isAnonymous = $params['row']['type'];
		
		if ($isAnonymous) {
			$label = 'Anonymous Visitor: '.$uid;
		} else {
			$feuser = t3lib_BEfunc::getRecord('fe_users', $feuser_id);
			$label = $feuser['name'];
		}
		
		$params['title'] = $label;
	}
	
	function getResponseLabel(&$params, &$pObj) {
		$uid = $params['row']['uid'];
		$response = t3lib_BEfunc::getRecord('tx_wecassessment_response', $uid);
				
		$category_id = $response['category_id'];
		$min = $response['min_value'];
		$max = $response['max_value'];
		
		$category = t3lib_BEfunc::getRecord('tx_wecassessment_category', $category_id);
		$category_title = $category['title'];
		
		$params['title'] = $category_title.': '.$min.'-'.$max;
	}
	
	/*
	 * @todo		Get rid of duplicated code!!!
	 */
	function getAnswerLabel(&$params, &$pObj) {
		$uid = $params['row']['uid'];
		$answer = t3lib_BEfunc::getRecord('tx_wecassessment_answer', $uid);

		$value = $answer['value'];
		$result_id = $answer['result_id'];
		$question_id = $answer['question_id'];
		
		$result = t3lib_BEfunc::getRecord('tx_wecassessment_result', $result_id);
		$feuser_id = $result['feuser_id'];
		
		if ($feuser_id == 0) {
			$result_title = 'Anonymous Visitor: '.$result['uid'];
		} else {
			$feuser = t3lib_BEfunc::getRecord('fe_users', $feuser_id);
			$result_title = $feuser['name'];
		}
		
		$question = t3lib_BEfunc::getRecord('tx_wecassessment_question', $question_id);
		$question_title = $question['text'];
		
		$params['title'] = $question_title;
		
	}
		

		
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cal/res/class.tx_cal_labels.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cal/res/class.tx_cal_labels.php']);
}  
  
?>
