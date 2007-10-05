<?php

class tx_wecassessment_tceforms_getmainfields {
	
	function getMainFields_preProcess($table,&$row, $tceform) {
		if($table == 'tx_wecassessment_question') {

			/* If we have posted data and a new record, preset values to what they were on the previous record */
			/*
			if(is_array($GLOBALS['HTTP_POST_VARS']['data']['tx_wecassessment_question']) && strstr($row['uid'], 'NEW')) {
				$postData = array_pop($GLOBALS['HTTP_POST_VARS']['data']['tx_wecassessment_question']);			
				$row['weight'] = $postData['weight'];
			}
			*/
		}
		
		if($table == 'tx_wecassessment_recommendation') {
			/* If we have posted data and a new record, preset values to what they were on the previous record */
			if(is_array($GLOBALS['HTTP_POST_VARS']['data']['tx_wecassessment_recommendation']) && strstr($row['uid'], 'NEW')) {
				$postData = array_pop($GLOBALS['HTTP_POST_VARS']['data']['tx_wecassessment_recommendation']);
				
				$row['type'] = $postData['type'];
				$row['question_id'] = $postData['question_id'];
				$row['category_id'] = $postData['category_id'];
				
				$range = $postData['max_value'] - $postData['min_value'];
				$row['min_value'] = $postData['max_value'];
				$row['max_value'] = $postData['max_value'] + $range;
			}
			
			/* Sniff for the parent table of the current IRRE child, set the type, and hide it */
			if(is_array($tceform->inline->inlineStructure['stable'])) {
				foreach($tceform->inline->inlineStructure['stable'] as $inlineStructure) {
					if($inlineStructure['field'] == 'recommendations') {
						switch($inlineStructure['table']) {
							case 'tx_wecassessment_question':
								//$GLOBALS['TCA']['tx_wecassessment_recommendation']['columns']['type']['config']['type'] = 'none';
								$row['type'] = 1;
								break;
							case 'tx_wecassessment_category':
								//$GLOBALS['TCA']['tx_wecassessment_recommendation']['columns']['type']['config']['type'] = 'none';
								$row['type'] = 0;
								break;
						}
					}
				}
			}
		}
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/backend/class.tx_wecassessment_tceforms_getmainfields.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/backend/class.tx_wecassessment_tceforms_getmainfields.php']);
}

?>