<?php

class tx_wecassessment_tceforms_getmainfields {
	
	function getMainFields_preProcess($table,&$row, $tceform) {
		if($table == 'tx_wecassessment_question' or $table == 'tx_wecassessment_category') {
			
			/* If we're in a question or a category, turn off sorting for questions */
			unset($GLOBALS['TCA']['tx_wecassessment_question']['ctrl']['sortby']);
			$GLOBALS['TCA']['tx_wecassessment_question']['ctrl']['default_sortby'] = "ORDER BY uid";
		}
		
		if($table == 'tx_wecassessment_recommendation') {
			/* If we have posted data and a new record, preset values to what they were on the previous record */
			if(is_array($GLOBALS['HTTP_POST_VARS']['data']['tx_wecassessment_recommendation']) && strstr($row['uid'], 'NEW')) {
				$postData = array_pop($GLOBALS['HTTP_POST_VARS']['data']['tx_wecassessment_recommendation']);

				$pid = $row['pid'];
				if ($pid < 0)	{
					$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('pid', $table, 'uid='.abs($pid));
					$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
					$pid = intval($row['pid']);
				}
				$assessmentClass = t3lib_div::makeInstanceClassName('tx_wecassessment_assessment');
				$assessment = new $assessmentClass(0, $pid);
				
				
				$row['type'] = $postData['type'];
				$row['question_id'] = $postData['question_id'];
				$row['category_id'] = $postData['category_id'];
				
				$range = $postData['max_value'] - $postData['min_value'];
				$row['min_value'] = $postData['max_value'];
				$row['max_value'] = $postData['max_value'] + $range;
				
				if($assessment->getMaximumValue() != 0 && $row['min_value'] > $assessment->getMaximumValue()) {
					$row['min_value'] = $assessment->getMaximumValue();
				}
				
				if($assessment->getMaximumValue() != 0 && $row['max_value'] > $assessment->getMaximumValue()) {
					$row['max_value'] = $assessment->getMaximumValue();
				}
				
			}
			
			/* Sniff for the parent table of the current IRRE child, set the type, and hide it */
			if(is_array($tceform->inline->inlineStructure['stable'])) {
				foreach($tceform->inline->inlineStructure['stable'] as $inlineStructure) {
					if($inlineStructure['field'] == 'recommendations') {
						switch($inlineStructure['table']) {
							case 'tx_wecassessment_question':
								$GLOBALS['TCA']['tx_wecassessment_recommendation']['columns']['type']['config']['type'] = 'user';
								$GLOBALS['TCA']['tx_wecassessment_recommendation']['columns']['type']['config']['userFunc'] = 'EXT:wec_assessment/backend/class.tx_wecassessment_results.php:tx_wecassessment_results->displayHiddenRecommendationType';
								$GLOBALS['TCA']['tx_wecassessment_recommendation']['columns']['type']['config']['noTableWrapping'] = true;
								$row['type'] = 1;
								break;
							case 'tx_wecassessment_category':
								$GLOBALS['TCA']['tx_wecassessment_recommendation']['columns']['type']['config']['type'] = 'user';
								$GLOBALS['TCA']['tx_wecassessment_recommendation']['columns']['type']['config']['userFunc'] = 'EXT:wec_assessment/backend/class.tx_wecassessment_results.php:tx_wecassessment_results->displayHiddenRecommendationType';
								$GLOBALS['TCA']['tx_wecassessment_recommendation']['columns']['type']['config']['noTableWrapping'] = true;
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