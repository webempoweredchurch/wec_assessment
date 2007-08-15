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

require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_response.php');
require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_category.php');
require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_assessment.php');

class tx_wecassessment_flexform {
	
	
	/**
	 * Common initialization function.
	 * @param		array		The array of all FlexForm elements.
	 * @param		array		???
	 * @return		none
	 */
	function init($PA, $fobj) {
		$this->PA = $PA;
		$flexform = $this->PA['row']['pi_flexform'];
		$assessmentClass = t3lib_div::makeInstanceClassName('tx_wecassessment_assessment');
		$this->assessment = new $assessmentClass($PA['row']['uid'], $PA['row']['pid'], '', t3lib_div::xml2array($flexform));
	}

	/**
	 * Gets the value of partcular FlexForm key on a particular sheet.
	 * @param		string		The name of the sheet.
	 * @param		string		The name of the FlexForm field.
	 * @return		mixed		The value of the FlexForm field.
	 */
	function getFlexFormValue($sheet, $field) {
		$flexform = $this->PA['row']['pi_flexform'];
		$ffArray = t3lib_div::xml2array($flexform);

		if(is_array($ffArray)) {
			$value = $ffArray['data'][$sheet]['lDEF'][$key]['vDEF'];
		}
		 
		return $value;
	}
	

	
	/**
	 * Gets the HTML for the scale form labels.
	 *
	 * @param		array		The array of all FlexForm elements.
	 * @param		array		fobj
	 * @return		string		The HTML for the scale form.
	 */
	function getScaleForm($PA, $fobj) {
		$this->init($PA, $fobj);
		$labels = $this->assessment->getAnswerSet();
		
		/* For each possible value, build the form elements. */
		for($i=$this->assessment->getMinimumValue(); $i<=$this->assessment->getMaximumValue(); $i++) {
			$output[] = '<div><label for="tx_wecassessment_label_'.$i.'">'.$i.'</label>
								<input name="data[tt_content]['.$this->assessment->getUID().'][pi_flexform][data][labels][lDEF][scale_label][vDEF]['.$i.']" id="tx_wec_assessment_label_'.$i.'" value="'.$labels[$i].'"/></div>';
		}			
		return implode(chr(10), $output);
	}
	
	
	/**
	 * Gets the HTML for the range checking validation.
	 *
	 * @param		array
	 * @param		array
	 * @return		string		The HTML for the range checking validation
	 * @todo		Add validity checking for more than 2 levels deep.
	 */
	function getValidationErrors($PA, $fobj) {		
		$this->init($PA, $fobj);
		
		$table = "tx_wecassessment_response";		
		$content = array();
				
		$topLevelCategories = tx_wecassessment_category::findAllParents($this->assessment->getPID());
		foreach($topLevelCategories as $topLevelCategory) {
			/* Check the validity of every top level category */
			if(!$topLevelCategory->valid($this->assessment->getMinimumValue(), $this->assessment->getMaximumValue())) {
				$content[] = $this->displayCategoryErrors('', $topLevelCategory);
			}
			
			/* Check the validity of each child */
			$children = $topLevelCategory->getChildCategories();			
			if(is_array($children)) {

				foreach($children as $child) {
	
					/* If the category isnt valid, add the error messages */
					if(!$child->valid($this->assessment->getMinimumValue(), $this->assessment->getMaximumValue())) {
						$content[] = $this->displayCategoryErrors($topLevelCategory->getTitle(), $child);
					}
				}
			}
		}
		
		if(sizeof($content) == 0) {
			$content[] = 'No errors found!';
		}
		
		return implode(chr(10), $content);
	
	}
	
	function displayCategoryErrors($parentTitle, $category) {
		$content = array();
		
		if($parentTitle) {		
			$title = $parentTitle.' : '.$category->getTitle();
		} else {
			$title = $category->getTitle();
		}
		$content[] = '<h3>'.$title.'</h3>';

		$errors = $category->getValidationErrors();
		
		$content[] = '<ul>';
		foreach($errors as $error) {
			$errorString = tx_wecassessment_flexform::errorToString($error);
			$content[] = '<li>'.$errorString.'</li>';
		}
		$content[] = '</ul>';
		
		return implode(chr(10), $content);
	}
	
	function errorToString($errorArray) {
		$uid1 = $errorArray['uid1'];
		$uid2 = $errorArray['uid2'];
		$message = $errorArray['message'];
		$return = $message.'<br />';
		
		if($uid1) {
			$response1 = tx_wecassessment_response::find($uid1);
			$uid1Link = tx_wecassessment_flexform::returnEditLink($uid1, $response1->getLabel(), $response1->getTableName());
			$return .= $uid1Link.'<br />';
		}
		
		if($uid2) {
			$response2 = tx_wecassessment_response::find($uid2);
			$uid2Link = tx_wecassessment_flexform::returnEditLink($uid2, $response2->getLabel(), $response2->getTableName());
			$return .= $uid2Link.'<br />';
		}
		
		return $return;
	}
	
	function displayResponse($response, $errors) {
		
		$content = array();	
		$link = $this->returnEditLink($response->getUID(), 'Edit Record', 'tx_wecassessment_response');
		
		$content[] = '<div class="tx_wecassessment_reponse">';
		$content[] = '<h3>'.$response->getMinValue().' - '.$response->getMaxValue().'</h3>';
		$content[] = '</div>';
						
		return implode(chr(10), $content);
	}
	
	function getErrors($response, $errors, $type='') {
		foreach($errors as $error) {
			if($error->getResponseUID() == $response->getUID() && $error->getType() == $type) {
				$filteredErrors[] = $error;
			}
		}
		
		return $filteredErrors;
	}
	
	function displayError($error) {
		$content = array();
		
		$content[] = $error->getMessage();
		$content[] = $this->returnEditLink($response->getUID(), 'Edit Record', 'tx_wecassessment_response'); 

		if($error->getUID2()) {
			$content[] = $this->returnEditLink($response->getUID(), 'Edit Record', 'tx_wecassessment_response');
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
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/backend/class.tx_wecassessment_flexform.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/backend/class.tx_wecassessment_flexform.php']);
}


?>