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
require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_modelbase.php');

class tx_wecassessment_assessment extends tx_wecassessment_modelbase {
	
	var $_minimumValue = 0;
	var $_maximumValue = 3;
	var $_answerSet = array(
		'0' => 'Never',
		'1' => 'Rarely',
		'2' => 'Sometimes.',
		'3' => 'Always',
	);
	var $_usePaging = false;
	var $_questionsPerPage = 0;
	var $_pageNumber = 0;
	var $_sorting = 'random';
	
	var $_result;
	var $_pid = 0;
	var $_uid = 0;
	
	/**
	 * Default constructor.
	 */
	function tx_wecassessment_assessment($uid=0, $pid=0, $conf='', $flexform='') {
		$this->_uid = $uid;
		$this->_pid = $pid;
		
		if(is_array($conf)) {
			$this->_usePaging = $conf['usePaging'];
			$this->_questionsPerPage = $conf['questionsPerPage'];
			$this->_sorting = $conf['sorting'];
			
			$this->_minimumValue = $conf['minimumValue'];
			$this->_maximumValue = $conf['maximumValue'];
			
			$this->_answerSet = $conf['answerLabels.'];
		}
		
		if(is_array($flexform)) {
			$this->_usePaging = $this->pi_getFFvalue($flexform, 'paging', 'general');
			$this->_questionsPerPage = $this->pi_getFFvalue($flexform, 'perPage', 'general');
			$this->_sorting = $this->pi_getFFvalue($flexform, 'sorting', 'general');
			
			$this->_minimumValue = $this->pi_getFFvalue($flexform, 'minRange', 'general');
			$this->_maximumValue = $this->pi_getFFvalue($flexform, 'maxRange', 'general');
			$this->_answerSet = $this->pi_getFFValue($flexform, 'scale_label', 'labels');
		}
		
	}
	
	/**
	 * Creates an array for data substitution into the cObj.
	 *
	 * @return		Array of assessment values.
	 */
	function toArray() {
		return array(
			'totalPages' => $this->getTotalPages(),
			'currentPage' => $this->getPageNumber(),
			'percentComplete' => $this->getPercentComplete(),
		);
	}
	
	/**
	 * Gets the questions on the current page of the assessment.
	 *
	 * @return		Array of questions.
	 */
	function getQuestionsInPage() {
		$result = &$this->getResult();
		
		switch($this->getSorting()) {
			case 'backend':
				$grouping = '';
				$sorting = 'sorting';
				$randomize = 0;
				break;
			case 'category':
				$grouping = 'category_id';
				$sorting = '';
				$randomize = 0;
				break;
			case 'random':
				$grouping = '';
				$sorting = '';
				$randomize = 1;
				break;
			default:
				$grouping = '';
				$sorting = '';
				$randomize = 0;
				break;
		}
		
		if($this->usePaging()) {
			$questions = $result->getQuestionsInPage($this->getPageNumber(), $this->getQuestionsPerPage(), $grouping, $sorting, $randomize);
		} else {
			$questions = $result->getQuestions($grouping, $sorting, $randomize);
		}
		
		return $questions;
	}
	
	function getUID() {
		return $this->_uid;
	}
	
	function setUID($value) {
		$this->_uid = $value;
	}
	
	function getPID() {
		return $this->_pid;
	}

	function setPID($value) {
		$this->_pid = $value;
	}

	function getAnswerSet() {
		/* Clean up the answerSet if we have bad values */
		if(count($this->_answerSet) > $this->getAnswerCount) {
			foreach($this->_answerSet as $value => $label) {
				if($value < $this->getMinimumValue() || $value > $this->getMaximumValue()) {
					unset($this->_answerSet[$value]);
				}
			}
		}
		
		return $this->_answerSet;
	}
	
	function getAnswerCount() {
		return $this->getMaximumValue() - $this->getMinimumValue() + 1;
	}
	
	function getMinimumValue() {
		return $this->_minimumValue;
	}
	
	function setMinimumValue($value) {
		$this->_minimumValue = $value;
	}
	
	function getMaximumValue() {
		return $this->_maximumValue;
	}
	
	function setMaximumValue($value) {
		$this->_maximumValue = $value;
	}
	
	function usePaging() {
		return $this->_usePaging;
	}
	
	function setPaging($value) {
		$this->_usePaging = $value;
	}
	
	function getQuestionsPerPage() {
		return $this->_questionsPerPage;
	}
	
	/**
	 * Returns the total number of pages in the current assessment.
	 *
	 * @return		The total number of pages in the current assessment.
	 */
	function getTotalPages() {
		if($this->getQuestionsPerPage() == 0 || !$this->usePaging()) {
			$totalPages = 1;
		} else {
			$result = &$this->getResult();
			$totalQuestions = count($result->getQuestions());
			$totalPages = ceil($totalQuestions / $this->getQuestionsPerPage());
		}
		
		return $totalPages;
	}
	
	
	/**
	 * Gets the current page number.
	 */
	function getPageNumber() {
		return $this->_pageNumber;
	}
	
	/**
	 * Sets the current page number.
	 */
	function setPageNumber($pageNumber) {
		$this->_pageNumber = $pageNumber;
	}
	
	function getNextPageNumber() {
		return $this->_pageNumber + 1;
	}
	
	function setQuestionsPerPage($value) {
		$this->_questionsPerPage = $value;
	}
	
	function getPercentComplete() {
		return floor(($this->getPageNumber() - 1) / $this->getTotalPages() * 100);
	}
	
	function getSorting() {
		return $this->_sorting;
	}
	
	function setSorting($sorting) {
		$this->_sorting = $sorting;
	}
	
	function getResult() {
		if(!$this->_result) {			
			/* Get the result, either from the DB or the session */
			$this->_result = tx_wecassessment_result::findCurrent($this->_pid);
		}
		return $this->_result;
	}
	
	
	
	
	/**
	 * Return value from somewhere inside a FlexForm structure
	 *
	 * @param	array		FlexForm data
	 * @param	string		Field name to extract. Can be given like "test/el/2/test/el/field_templateObject" where each part will dig a level deeper in the FlexForm data.
	 * @param	string		Sheet pointer, eg. "sDEF"
	 * @param	string		Language pointer, eg. "lDEF"
	 * @param	string		Value pointer, eg. "vDEF"
	 * @return	string		The content.
	 */
	function pi_getFFvalue($T3FlexForm_array,$fieldName,$sheet='sDEF',$lang='lDEF',$value='vDEF')	{
		$sheetArray = is_array($T3FlexForm_array) ? $T3FlexForm_array['data'][$sheet][$lang] : '';
		if (is_array($sheetArray))	{
			return $this->pi_getFFvalueFromSheetArray($sheetArray,explode('/',$fieldName),$value);
		}
	}

	/**
	 * Returns part of $sheetArray pointed to by the keys in $fieldNameArray
	 *
	 * @param	array		Multidimensiona array, typically FlexForm contents
	 * @param	array		Array where each value points to a key in the FlexForms content - the input array will have the value returned pointed to by these keys. All integer keys will not take their integer counterparts, but rather traverse the current position in the array an return element number X (whether this is right behavior is not settled yet...)
	 * @param	string		Value for outermost key, typ. "vDEF" depending on language.
	 * @return	mixed		The value, typ. string.
	 * @access private
	 * @see pi_getFFvalue()
	 */
	function pi_getFFvalueFromSheetArray($sheetArray,$fieldNameArr,$value)	{

		$tempArr=$sheetArray;
		foreach($fieldNameArr as $k => $v)	{
			if (t3lib_div::testInt($v))	{
				if (is_array($tempArr))	{
					$c=0;
					foreach($tempArr as $values)	{
						if ($c==$v)	{
							#debug($values);
							$tempArr=$values;
							break;
						}
						$c++;
					}
				}
			} else {
				$tempArr = $tempArr[$v];
			}
		}
		return $tempArr[$value];
	}
	
	
}