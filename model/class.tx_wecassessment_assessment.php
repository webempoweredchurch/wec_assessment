<?php
/***************************************************************
* Copyright notice
*
* (c) 2007-2008 Christian Technology Ministries International Inc.
* All rights reserved
*
* This file is part of the Web-Empowered Church (WEC)
* (http://WebEmpoweredChurch.org) ministry of Christian Technology Ministries 
* International (http://CTMIinc.org). The WEC is developing TYPO3-based
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

require_once(t3lib_extMgm::extPath('wec_assessment') . 'model/class.tx_wecassessment_recommendationcontainer.php');
require_once(t3lib_extMgm::extPath('wec_assessment') . 'model/class.tx_wecassessment_result.php');
require_once(t3lib_extMgm::extPath('wec_assessment') . 'model/class.tx_wecassessment_recommendation_assessment.php');
require_once(t3lib_extMgm::extPath('wec_assessment') . 'model/class.tx_wecassessment_category.php');

define(SINGLE_PAGE_DISPLAY, 0);
define(MULTI_PAGE_DISPLAY, 1);
define(SLIDER_DISPLAY, 2);

/**
 * Data model for the main Assessment record.
 *
 * @author	Web-Empowered Church Team <assessment@webempoweredchurch.org>
 * @package TYPO3
 * @subpackage tx_wecassessment
 */
class tx_wecassessment_assessment extends tx_wecassessment_recommendationcontainer {
	
	var $_minimumValue;
	var $_maximumValue;
	var $_answerSet;
	var $_displayMode;
	var $_questionsPerPage;
	var $_categories;
	var $_pageNumber;
	var $_sorting;
	var $_skipToUnansweredQuestions;
	
	var $_result;
	var $_pid;
	var $_uid;
	
	var $_ttcontentRow;
	
	var $_recommendationClass = 'tx_wecassessment_recommendation_assessment';
	
	/**
	 * Default constructor.
	 * @todo 	Initialize conf and flexform if they're not provided.
	 */
	function tx_wecassessment_assessment($uid=0, $pid=0, $conf=null, $flexform=null) {

		if(!is_object($GLOBALS['LANG'])) {
			require_once(t3lib_extMgm::extPath('lang') . 'lang.php');
			$GLOBALS['LANG'] = t3lib_div::makeInstance('language');
			
			if(TYPO3_MODE == 'BE') {
				$GLOBALS['LANG']->init($BE_USER->uc['lang']);
			} else {
				$GLOBALS['LANG']->init($GLOBALS['TSFE']->config['config']['language']);
			}
		}

		$GLOBALS['LANG']->includeLLFile('EXT:wec_assessment/locallang.xml');
		
		// @todo  Makes sure we have a good PID that actually has an assessment Flexform.  What are the implications of changing the PID though?
		$this->_pid = $this->checkPID($pid);
		
		// @todo  Are there times in the backend that we don't need TSFE?
		if(!$GLOBALS['TSFE']) {
			$this->initializeFrontend($this->getPID());
		}
		
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
			$this->setDisplayMode($conf['displayMode']);
			
			$this->_questionsPerPage = $conf['questionsPerPage'];
			$this->_sorting = $conf['sorting'];
			
			$this->_minimumValue = $conf['minimumValue'];
			$this->_maximumValue = $conf['maximumValue'];
			
			$this->_answerSet = $conf['answerLabels.'];
		}
		
		if(is_array($flexform)) {
			if($displayMode = $this->pi_getFFvalue($flexform, 'displayMode', 'general')) {
				$this->setDisplayMode($displayMode);
			}
			
			if($questionsPerPage = $this->pi_getFFvalue($flexform, 'perPage', 'general')) {
				$this->_questionsPerPage = $questionsPerPage;
			}
			
			if($sorting = $this->pi_getFFvalue($flexform, 'sorting', 'general')) {
				$this->_sorting = $sorting;
			}
			
			$minimumValue = $this->pi_getFFvalue($flexform, 'minRange', 'general');
			if($minimumValue != '') {
				$this->_minimumValue = $minimumValue;
			}
			
			$maximumValue = $this->pi_getFFvalue($flexform, 'maxRange', 'general');
			if($maximumValue !='') {
				$this->_maximumValue = $maximumValue;
			}
			
			$hasAnswers = false;
			$answerSet = $this->pi_getFFValue($flexform, 'scale_label', 'labels');
			foreach((array) $answerSet as $value => $label) {
				if($label != '') {
					$hasAnswers = true;
					break;
				}
			}
			
			if($hasAnswers) {
				$this->_answerSet = $answerSet;
			}
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
		
		if($this->getDisplayMode() == MULTI_PAGE_DISPLAY) {
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
	 */
	function getLabel() {
		return $GLOBALS['LANG']->getLL('total_assessment');
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
		// Clean up the answerSet if we have bad values
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
	 * Checks the display mode for the assessment.
	 * @return		boolean
	 */
	function getDisplayMode() {
		return $this->_displayMode;
	}
	
	/**
	 * Sets the paging option.
	 * @param		string		Display mode for the assessment.
	 * @return		none
	 */
	function setDisplayMode($value) {
		switch($value) {
			case 'slider':
				$this->_displayMode = SLIDER_DISPLAY;
				break;
			case 'multi':
				$this->_displayMode = MULTI_PAGE_DISPLAY;
				break;
			default:
				$this->_displayMode = SINGLE_DISPLAY;
				break;
		}
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
		if($this->getQuestionsPerPage() == 0 || ($this->getDisplayMode() !== MULTI_PAGE_DISPLAY)) {
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
		
		if($this->skipToUnansweredQuestions()) {
			$nextPageNumber = $this->getFirstIncompletePage();
		} else {
			if($this->getTotalPages() == $this->_pageNumber) {
				$nextPageNumber = $this->_pageNumber;
			} else {
				$nextPageNumber = $this->_pageNumber + 1;
			}
		}

		return $nextPageNumber;
	}
	
	/**
	 * Sets the skipToUnansweredQuestions value.  Generally based on piVars.
	 * @param		boolean		The value.
	 * @return		none
	 */
	function setSkipToUnansweredQuestions($value) {
		$this->_skipToUnansweredQuestions = $value;
	}

	/**
	 * Gets the skipToUnansweredQuestions property.
	 *
	 * @return		boolean
	 */
	function skipToUnansweredQuestions() {
		if(!isset($this->_skipToUnansweredQuestions)) {
			if(($this->getDisplayMode() == MULTI_PAGE_DISPLAY) &&
			   ($this->getPageNumber() == $this->getTotalPages())) {
				$this->_skipToUnansweredQuestions = true;
			} else {
				$this->_skipToUnansweredQuestions = false;
			}
		}
	
		return $this->_skipToUnansweredQuestions;
	}
	
	/**
	 * Gets the percentage complete.
	 * @return		integer		Gets the percent.
	 */
	function getPercentComplete() {
		return floor(($this->getPageNumber()) / $this->getTotalPages() * 100);
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
			// Get the result, either from the DB or the session
			$this->_result = tx_wecassessment_result::findCurrent($this->_pid);
		}
		return $this->_result;
	}
	
	/**
	 * Returns the first page that has unanswered questions left for later redirection
	 *
	 * @return int page number or 0 if none incomplete
	 **/
	function getFirstIncompletePage() {
		$unansweredQuestions = $this->getResult()->getUnansweredQuestions($this->grouping, $this->getSorting(), $this->randomize);
		
		if(empty($unansweredQuestions)) {
			return 0;
		} else {
			foreach($unansweredQuestions as $question) {
				$pageNumber = ceil(($question->getIndex() + 1) / $this->getQuestionsPerPage());
				if($pageNumber != $this->getPageNumber()) {
					break;
				}
			}
			
			return $pageNumber;			
		}
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
		
		// Build the category array
		$categoryArray = array();
		foreach((array) $answers as $answer) {
			$category = tx_wecassessment_category::find($answer->getCategoryUID());
			if(is_object($category)) {
				$sorting = $category->getSorting();
				$categoryArray[$sorting]['uid'] = $category->getUID();
				$categoryArray[$sorting]['answers'][$answer->getQuestionUID()] = $answer;
				$categoryArray[$sorting]['totalScore'] += $answer->getWeightedScore();
				
				// @todo	This only makes sense when minimumValue = 0.  Not sure what to do otherwise.
				if ($answer->getWeight() > 0) {
					$categoryArray[$sorting]['maxScore'] += $answer->getWeight() * $this->getMaximumValue();
					$categoryArray[$sorting]['weightedAnswerCount']  += $answer->getWeight();
					$weightedAnswerCount += $answer->getWeight();
				} else {
					$categoryArray[$sorting]['maxScore'] += $answer->getWeight() * $this->getMinimumValue();
				}
				
				$totalAssessmentScore += $answer->getWeightedScore();
				$maxAssessmentScore += $answer->getWeight() * $this->getMaximumValue();
			}
		}
		
		ksort($categoryArray);

		// Hack to include perfect score
		if($maxAssessmentScore == $totalAssessmentScore) {
			$totalAssessmentScore -= .0001;
		}
		
		// Total Assessment Recommendations
		if($weightedAnswerCount == 0) {
			$score = 0;
		} else {
			$score = $totalAssessmentScore / $weightedAnswerCount;
		}
		
		// Don't allow a score below the minimum (due to negative weighting)
		if($score < $minValue) {
			$score = $minValue;
		}
		
		$recommendation = &$this->calculateRecommendation($score);
		if(is_object($recommendation)) {
			$recommendation->setMaxScore($this->getMaximumValue());
			$recommendations[0] = $recommendation;
		}
		
		// Category Recommendations
		foreach((array) $categoryArray as $sorting => $children) {
			$category = tx_wecassessment_category::find($children['uid']);
			
			// Hack to include perfect score
			if($children['maxScore'] == $children['totalScore']) {
				$children['totalScore'] -= .0001;
			}			
			
			if($children['weightedAnswerCount'] == 0) {
				$categoryScore = 0;
			} else {
				$categoryScore = $children['totalScore'] / ($children['weightedAnswerCount']);
			}
			
			// Don't allow a score below the minimum (due to negative weighting)
			if($categoryscore < $minValue) {
				$categoryScore = $minValue;
			}
			
			$recommendation = &$category->calculateRecommendation($categoryScore);
			
			if(is_object($recommendation)) {
				$recommendation->setMaxScore($this->getMaximumValue());
				$recommendations[] = $recommendation;
			}
			
			foreach((array) $children['answers'] as $answer) {
				$question = $answer->getQuestion();
				$questionScore = $answer->getScore();
				
				// Hack to include perfect score
				if($questionScore == $this->getMaximumValue()) {
					$questionScore -= .0001;
				}
				
				// Don't allow a score below the minimum (due to negative weighting)
				if($questionScore < $minValue) {
					$questionScore = $minValue;
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
		define('PATH_tslib', PATH_site . 'typo3/sysext/cms/tslib/');
		require_once (PATH_tslib . '/class.tslib_content.php');
		require_once(t3lib_extMgm::extPath('wec_assessment') . 'backend/class.tx_wecassessment_tsfe.php');
		require_once(PATH_t3lib . 'class.t3lib_userauth.php');
		require_once(PATH_tslib . 'class.tslib_feuserauth.php');
		require_once(PATH_t3lib . 'class.t3lib_befunc.php');
		require_once(PATH_t3lib . 'class.t3lib_timetrack.php');
		require_once(PATH_t3lib . 'class.t3lib_tsparser_ext.php');
		require_once(PATH_t3lib . 'class.t3lib_page.php');

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
		if(!isset($this->_ttcontentRow)) {
			$this->checkPID($pid);
		}
		
		return t3lib_div::xml2Array($this->_ttcontentRow['pi_flexform']);
	}
	
	/**
	 * Gets the UID of the assessment plugin on a specific page.
	 * @param		integer		The PID to perform the lookup on.
	 * @return		integer		The UID.
	 */
	function lookupUID($pid) {
		if(!isset($this->_ttcontentRow)) {
			$this->checkPID($pid);
		}
		return $this->_ttcontentRow['uid'];
	}
	
	/**
	 * Checks to the make sure that the pid actually has an assesment plugin on the page.
	 * If not, find  one to use.
	 *
	 * @param		integer		The PID to be used for TSFE initialization, etc.
	 * @return		integer
	 */
	function checkPID($pid) {
		$flexformPID = $pid;
		
		$fields = '*';
		$table = 'tt_content';
		$where = 'tt_content.list_type="wec_assessment_pi1" AND tt_content.deleted=0';
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery($fields, $table, $where);
		
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)) {
				$flexformPID = $row['pid'];
				$ttContentRow = $row;
				
				if($pid == $row['pid']) {
					break;
				}
		}
		
		$this->_ttcontentRow = $ttContentRow;

		return $flexformPID;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/model/class.tx_wecassessment_assessment.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/model/class.tx_wecassessment_assessment.php']);
}
