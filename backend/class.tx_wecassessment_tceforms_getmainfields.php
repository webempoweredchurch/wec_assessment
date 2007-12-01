<?php

class tx_wecassessment_tceforms_getmainfields {
	
	function getMainFields_preProcess($table,&$row, $tceform) {
		if($row['CType'] == 'list' && $row['list_type'] == 'wec_assessment_pi1') {
			$assessmentClass = t3lib_div::makeInstanceClassName('tx_wecassessment_assessment');
			$assessment = new $assessmentClass($row['uid'], $row['pid'], '', t3lib_div::xml2array($row['pi_flexform']));
			
			$content = array();
			$minValue = $assessment->getMinimumValue();
			$maxValue = $assessment->getMaximumValue();

			/* Check the validity of the total recommendation */
			if(!$assessment->valid($minValue, $maxValue)) {
				$content[] = $this->displayContainerErrors('', $assessment);
			}

			/* Check all categories */
			$categories = tx_wecassessment_category::findAll($assessment->getPID());
			foreach($categories as $category) {
				if(!$category->valid($minValue, $maxValue)) {
					$content[] = $this->displayContainerErrors('', $category);
				}

				/* Check the validity of each question */
				$questions = $category->findQuestions();
				foreach($questions as $question) {
					if(!$question->valid($minValue, $maxValue)) {
						$content[] = $this->displayContainerErrors($this->crop($category->getLabel()), $question);
					}
				}
			}
			
			if(count($content) > 0) {
				$tceform->extraFormHeaders[] = '<div style="background-color: yellow; border: 2px solid black; width: 88%; padding: 15px; margin-bottom: 15px;">'.implode(chr(10), $content).'</div>';
			}
		}
		
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
	
	function displayContainerErrors($parentTitle, $container) {
		$content = array();
		
		if($parentTitle) {		
			$title = $parentTitle.' : '.$this->crop($container->getLabel());
		} else {
			$title = $this->crop($container->getLabel());
		}
		$content[] = '<h3 style="background-color:yellow;">'.$title.'</h3>';

		$errors = $container->getValidationErrors();
		
		if(is_array($errors) && count($errors)) {
			$content[] = '<ul>';
			foreach($errors as $error) {
				$errorString = tx_wecassessment_tceforms_getmainfields::errorToString($error);
				$content[] = '<li>'.$errorString.'</li>';
			}
			$content[] = '</ul>';
		}
		
		return implode(chr(10), $content);
	}
	
	function errorToString($errorArray) {
		$uid1 = $errorArray['uid1'];
		$uid2 = $errorArray['uid2'];
		$message = $errorArray['message'];
		$return = $message.'<br />';
		
		if($uid1) {
			$recommendation1 = tx_wecassessment_recommendation::find($uid1);
			$uid1Link = tx_wecassessment_tceforms_getmainfields::returnEditLink($uid1, $this->crop($recommendation1->getLabel()), $recommendation1->getTableName());
			$return .= $uid1Link.'<br />';
		}
		
		if($uid2) {
			$recommendation2 = tx_wecassessment_recommendation::find($uid2);
			$uid2Link = tx_wecassessment_tceforms_getmainfields::returnEditLink($uid2, $this->crop($recommendation2->getLabel()), $recommendation2->getTableName());
			$return .= $uid2Link.'<br />';
		}
		
		return $return;
	}
	
	function displayRecommendation($recommendation, $errors) {
		
		$content = array();	
		$link = $this->returnEditLink($recommendation->getUID(), 'Edit Record', 'tx_wecassessment_recommendation');
		
		$content[] = '<div class="tx_wecassessment_response">';
		$content[] = '<h3 style="background-color:yellow;">'.$recommendation->getMinValue().' - '.$recommendation->getMaxValue().'</h3>';
		$content[] = '</div>';
						
		return implode(chr(10), $content);
	}
	
	function getErrors($recommendation, $errors, $type='') {
		foreach($errors as $error) {
			if($error->getRecommendationUID() == $recommendation->getUID() && $error->getType() == $type) {
				$filteredErrors[] = $error;
			}
		}
		
		return $filteredErrors;
	}
	
	function displayError($error) {
		$content = array();
		
		$content[] = $error->getMessage();
		$content[] = $this->returnEditLink($recommendation->getUID(), 'Edit Record', 'tx_wecassessment_recommendation'); 

		if($error->getUID2()) {
			$content[] = $this->returnEditLink($recommendation->getUID(), 'Edit Record', 'tx_wecassessment_recommendation');
		}
		
		return implode(chr(10), $content);
	}
	
	
	
	function returnNewLink($title,$tablename = false) {
		if (empty($tablename)) $tablename = $this->tconf['name'];
		$params = '&edit['.$tablename.']['.$this->spid.']=new';

		$out .=	'<a href="#" onclick="'.
		t3lib_BEfunc::editOnClick($params,$GLOBALS['BACK_PATH']).
		'">';
		$out .= '<img'.t3lib_iconWorks::skinImg($GLOBALS['BACK_PATH'],'gfx/new_record.gif','width="11" height="12"').' title="Edit me" border="0" alt="" /></a>';
		$out .=	' <a href="#" onclick="'.
		t3lib_BEfunc::editOnClick($params,$GLOBALS['BACK_PATH']).
		'">';
		$out .= $title.'</a>';
		return $out;
	}



	function returnEditLink($uid,$title,$tablename = false) {
		if (empty($tablename)) $tablename = $this->tconf['name'];
		$params = '&edit['.$tablename.']['.$uid.']=edit';
		$out .=	'<a href="#" onclick="'.
		t3lib_BEfunc::editOnClick($params,$GLOBALS['BACK_PATH']).
		'">';
		$out .= $title;
		$out .= '<img'.t3lib_iconWorks::skinImg($GLOBALS['BACK_PATH'],'gfx/edit2.gif','width="11" height="12"').' title="Edit me" border="0" alt="" />';
		$out .= '</a>';
		return $out;
	}

	function crop($string) {
		$titleLength = $GLOBALS['BE_USER']->uc['titleLen'];
		return htmlspecialchars(t3lib_div::fixed_lgd_cs($string, $titleLength));
		
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/backend/class.tx_wecassessment_tceforms_getmainfields.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/backend/class.tx_wecassessment_tceforms_getmainfields.php']);
}

?>