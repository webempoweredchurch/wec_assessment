<?php

/***************************************************************
* Copyright notice
*
* (c) 2007 Foundation for Evangelism (info@evangelize.org)
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

require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_recommendationcontainer.php');
require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_result.php');
require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_recommendation_assessment.php');
require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_category.php');

/**
 * Data model for the main Assessment record.
 *
 * @author	Web-Empowered Church Team <assessment@webempoweredchurch.org>
 * @package TYPO3
 * @subpackage tx_wecassessment
 */
class tx_wecassessment_assessment extends tx_wecasssessment_recommendationcontainer {
	
	var $_minimumValue;
	var $_maximumValue;
	var $_answerSet;
	var $_usePaging;
	var $_questionsPerPage;
	var $_categories;
	var $_pageNumber;
	var $_sorting;
	
	var $_result;
	var $_pid;
	var $_uid;
	
	var $_recommendationClass = 'tx_wecassessment_recommendation_assessment';
	
	
	/**
	 * Default constructor.
	 * @todo 	Initialize conf and flexform if they're not provided.
	 */
	function tx_wecassessment_assessment($uid=0, $pid=0, $conf=null, $flexform=null) {
		$this->_pid = $pid;
		
		if($uid==0) {
			$this->_uid = $this->lookupUID($this->_pid);
		} else {
			$this->_uid = $uid;
		}
		
		if(!isset($conf)) {
			$conf = $this->getConf($this->_pid);
		}
		
		if(!isset($flexform)) {
			$flexform = $this->getFlexform($this->_pid);
		}
		
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


	/*************************************************************************
	 *
	 * Default getters and setters
	 *
	 ************************************************************************/
	
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
	
	/**
	 * Gets the categories for the current assessment.
	 * @return		array		Array of category objects.
	 */
	function getCategories() {
		if(!$this->_categories) {
			$this->_categories = tx_wecassessment_category::findAll($this->getPID());
		}
		
		return $this->_categories;
	}
	
	/**
	 * Gets the label for the assessment.
	 * @return		string		The assessment label.
	 * @todo 		Localize!
	 */
	function getlabel() {
		return 'Total Assessment';
	}
	
	/**
	 * Gets the UID of the assessment record.
	 * @return		integer		The UID.
	 */
	function getUID() {
		return $this->_uid;
	}
	
	
	/**
	 * Sets the UID of the assessment record.
	 * @param		integer		The UID.
	 * @return		none
	 */
	function setUID($value) {
		$this->_uid = $value;
	}
	
	/**
	 * Gets the PID of the assessment record.
	 * @return		integer		The PID.
	 */
	function getPID() {
		return $this->_pid;
	}

	/**
	 * Sets the PID of the assessment record.
	 * @param		integer		The PID.
	 * @return		none
	 */
	function setPID($value) {
		$this->_pid = $value;
	}
	
	/**
	 * Gets the answer set for the current assessment.
	 * @return		array		Array of answer objects.
	 */
	function getAnswerSet() {
		/* Clean up the answerSet if we have bad values */
		if(count($this->_answerSet) > $this->getAnswerCount) {
			foreach((array) $this->_answerSet as $value => $label) {
				if($value < $this->getMinimumValue() || $value > $this->getMaximumValue()) {
					unset($this->_answerSet[$value]);
				}
			}
		}
		
		return $this->_answerSet;
	}
	
	/**
	 * Get the number of answers available for each question.
	 * @return		integer		The answer count.
	 */
	function getAnswerCount() {
		return $this->getMaximumValue() - $this->getMinimumValue() + 1;
	}
	
	/**
	 * Gets the minimum value for the assessment.
	 * @return		integer		The minimum value.
	 */
	function getMinimumValue() {
		return $this->_minimumValue;
	}
	
	/**
	 * Sets the minimum value for the assessment.
	 * @param		integer		The minimum value.
	 * @return		none
	 */
	function setMinimumValue($value) {
		$this->_minimumValue = $value;
	}
	
	/**
	 * Gets the maximum value for the assessment.
	 * @return		integer		The maximum value.
	 */
	function getMaximumValue() {
		return $this->_maximumValue;
	}
	
	/**
	 * Sets the maximum value.
	 * @param		integer		The maximum value.
	 * @return		none
	 */
	function setMaximumValue($value) {
		$this->_maximumValue = $value;
	}
	
	/**
	 * Checks if paging should be used.
	 * @return		boolean
	 */
	function usePaging() {
		return $this->_usePaging;
	}
	
	/**
	 * Sets the paging option.
	 * @param		boolean		True if paging is enabled, otherwise false.
	 * @return		none
	 */
	function setPaging($value) {
		$this->_usePaging = $value;
	}
	
	/**
	 * Gets the number of questions on each page.
	 * @return		integer		The number of questions per page.
	 */
	function getQuestionsPerPage() {
		return $this->_questionsPerPage;
	}
	
	/**
	 * Sets the number of questions per page.
	 * @param		integer		The number of questions per page.
	 * @return		none
	 */
	function setQuestionsPerPage($value) {
		$this->_questionsPerPage = $value;
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
	
	/**
	 * Gets the next page number.
	 * @return		integer		The next page number.
	 */
	function getNextPageNumber() {
		return $this->_pageNumber + 1;
	}
	
	/**
	 * Gets the percentage complete.
	 * @return		integer		Gets the percent.
	 */
	function getPercentComplete() {
		return floor(($this->getPageNumber() - 1) / $this->getTotalPages() * 100);
	}
	
	/**
	 * Gets the sorting option for the current assessment.
	 * @return		integer		The sorting option.
	 */
	function getSorting() {
		return $this->_sorting;
	}
	
	/**
	 * Sets the sorting option for the current assessment.
	 * @param		integer		The sorting option.
	 * @return		none
	 */
	function setSorting($sorting) {
		$this->_sorting = $sorting;
	}
	
	/**
	 * Gets the result for the current user on this assessment.
	 * @return		object		The result object.
	 */
	function getResult() {
		if(!$this->_result) {			
			/* Get the result, either from the DB or the session */
			$this->_result = tx_wecassessment_result::findCurrent($this->_pid);
		}
		return $this->_result;
	}
	
	/**
	 * Sets the result.
	 * @param		object		Result object.
	 * @return		none
	 */
	function setResult($result) {
		$this->_result = $result;
	}
	
	/**
	 * Calculate all recommendations. This includes total assessment, categories, and questions.
	 * @return		array		Data structure with scores, recommendations, etc.
	 */
	function calculateAllRecommendations() {
		$totalAssessmentScore = 0;
		$maxAssessmentScore = 0;
		
		$recommendations = array();
		$minValue = $this->getMinimumValue();
		$maxValue = $this->getMaximumValue();
		
		$result = $this->getResult();
		$answers = $result->getAnswers();
		
		/* Build the category array */
		$categoryArray = array();
		foreach((array) $answers as $answer) {
			$category = tx_wecassessment_category::find($answer->getCategoryUID());
			$sorting = $category->getSorting();
			
			if(is_object($category)) {
				$categoryArray[$sorting]['uid'] = $category->getUID();
				$categoryArray[$sorting]['answers'][$answer->getQuestionUID()] = $answer;
				$categoryArray[$sorting]['totalScore'] += $answer->getWeightedScore();
				$categoryArray[$sorting]['maxScore'] += $answer->getWeight() * $this->getMaximumValue();
			
				$totalAssessmentScore += $answer->getWeightedScore();
				$maxAssessmentScore +- $answer->getWeight() * $this->getMaximumValue();
			}
		}
		
		ksort($categoryArray);
		
		/* Hack to include perfect score */
		if($maxAssessmentScore == $totalAssessmentScore) {
			$totalAssessmentScore -= .0001;
		}
		
		/* Total Assessment Recommendations */
		if(count($answers) == 0) {
			$score = 0;
		} else {
			$score = $totalAssessmentScore / count($answers);
		}
		$recommendation = &$this->calculateRecommendation($score);
		if(is_object($recommendation)) {
			$recommendation->setMaxScore($this->getMaximumValue());
			$recommendations[0] = $recommendation;
		}
		
		/* Category Recommendations */
		foreach((array) $categoryArray as $sorting => $children) {
			$category = tx_wecassessment_category::find($children['uid']);
			
			/* Hack to include perfect score */
			if($children['maxScore'] == $children['totalScore']) {
				$children['totalScore'] -= .0001;
			}			

			$categoryScore = $children['totalScore'] / count($children['answers']);
			$recommendation = &$category->calculateRecommendation($categoryScore);
			
			if(is_object($recommendation)) {
				$recommendation->setMaxScore($this->getMaximumValue());
				$recommendations[] = $recommendation;
			}
			
			foreach((array) $children['answers'] as $answer) {
				$question = $answer->getQuestion();
				$questionScore = $answer->getScore();
				
				/* Hack to include perfect score */
				if($questionScore == $this->getMaximumValue()) {
					$questionScore -= .0001;
				}
				
				$recommendation = $question->calculateRecommendation($questionScore);			
				if(is_object($recommendation)) {
					$recommendation->setMaxScore($this->getMaximumValue());
					$recommendations[] = $recommendation;
				}
			}
		}
		
		return $recommendations;
	}
	
	/**
	 * Calculate the recommendation for the total assessment.
	 * @param		integer		The score for the whole assessment.
	 * @return		object		The recommendation object.
	 */
	function calculateRecommendation($score) {
		return tx_wecassessment_recommendation_assessment::findByScore($score, $this->getUID());
	}	
	
	
	/*************************************************************************
	 *
	 * Utility Functions
	 *
	 ************************************************************************/
	
	
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
		foreach((array) $fieldNameArr as $k => $v)	{
			if (t3lib_div::testInt($v))	{
				if (is_array($tempArr))	{
					$c=0;
					foreach((array) $tempArr as $values)	{
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
	
	/**
	 * Creates a fake frontend instance.
	 * @param		integer		The page ID to initialize.
	 * @param		object		The frontend user object.
	 */
	function initializeFrontend($pid, $feUserObj=''){
		define('PATH_tslib', PATH_site.'typo3/sysext/cms/tslib/');
		require_once (PATH_tslib.'/class.tslib_content.php');
		require_once(t3lib_extMgm::extPath('wec_assessment').'backend/class.tx_wecassessment_tsfe.php');
		require_once(PATH_t3lib.'class.t3lib_userauth.php');
		require_once(PATH_tslib.'class.tslib_feuserauth.php');
		require_once(PATH_t3lib.'class.t3lib_befunc.php');
		require_once(PATH_t3lib.'class.t3lib_timetrack.php');
		require_once(PATH_t3lib.'class.t3lib_tsparser_ext.php');
		require_once(PATH_t3lib.'class.t3lib_page.php');

		$GLOBALS['TT'] = new t3lib_timeTrack;
	
		// ***********************************
		// Creating a fake $TSFE object
		// ***********************************
		$TSFEclassName = t3lib_div::makeInstanceClassName('tx_wecassessment_tsfe');
		$GLOBALS['TSFE'] = new $TSFEclassName($GLOBALS['TYPO3_CONF_VARS'], $pid, '0', 1, '', '','','');
		$GLOBALS['TSFE']->connectToMySQL();
		if($feUserObj==''){
			$GLOBALS['TSFE']->initFEuser();
		}else{
			$GLOBALS['TSFE']->fe_user = &$feUserObj;
		}

		$GLOBALS['TSFE']->fetch_the_id();
		$GLOBALS['TSFE']->getPageAndRootline();
		$GLOBALS['TSFE']->initTemplate();
		$GLOBALS['TSFE']->tmpl->getFileName_backPath = PATH_site;
		$GLOBALS['TSFE']->forceTemplateParsing = 1;
		$GLOBALS['TSFE']->getConfigArray();
	}
	
	/**
	 * Gets Typoscript configuration for the specified page.
	 * @param		integer		The page ID to initialize.
	 * @return		array		Typoscript array.
	 */
	function getConf($pid) {
		$this->initializeFrontend($pid);
		
		//we need to get the plugin setup to create correct source URLs
		$template = t3lib_div::makeInstance('t3lib_tsparser_ext'); // Defined global here!
		$template->tt_track = 0; 
		// Do not log time-performance information
		$template->init();
		$sys_page = t3lib_div::makeInstance('t3lib_pageSelect');
		$rootLine = $sys_page->getRootLine($pid);
		$template->runThroughTemplates($rootLine); // This generates the constants/config + hierarchy info for the template.
		$template->generateConfig();//
		$conf = $template->setup['plugin.']['tx_wecassessment_pi1.'];
		
		return $conf;
	}
	
	/**
	 * Gets an array of flexform data.
	 * @param		integer		The PID to fetch the assessment record from.
	 * @return		array		Array of flexform data.
	 */
	function getFlexform($pid) {
		$fields = 'pi_flexform';
		$tables = 'tt_content';
		$where = 'tt_content.list_type="wec_assessment_pi1" AND tt_content.deleted=0 AND pid='.$pid;
		list($row) = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($fields,$tables,$where);
		
		return t3lib_div::xml2Array($row['pi_flexform']);
	}
	
	/**
	 * Gets the UID of the assessment plugin on a specific page.
	 * @param		integer		The PID to perform the lookup on.
	 * @return		integer		The UID.
	 */
	function lookupUID($pid) {
		$fields = 'uid';
		$tables = 'tt_content';
		$where = 'tt_content.list_type="wec_assessment_pi1" AND tt_content.deleted=0 AND pid='.$pid;
		list($row) = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($fields,$tables,$where);
		
		return $row['uid'];
	}
	
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/model/class.tx_wecassessment_assessment.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/model/class.tx_wecassessment_assessment.php']);
}
