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

require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_modelbase.php');

define('TX_WECASSESSMENT_RECOMMENDATION_CATEGORY', 0);
define('TX_WECASSESSMENT_RECOMMENDATION_QUESTION', 1);
define('TX_WECASSESSMENT_RECOMMENDATION_ASSESSMENT', 2);

/**
 * Data models for Assessment Recommendation.
 *
 * @author	Web-Empowered Church Team <assessment@webempoweredchurch.org>
 * @package TYPO3
 * @subpackage tx_wecassessment
 */

class tx_wecassessment_recommendation extends tx_wecassessment_modelbase {
	
	var $_uid;
	var $_text;
	var $_min_value;
	var $_max_value;
	var $_score;
	
	var $_validationErrors;
		
	/**
	 * Default constructor.
	 *
	 * @param		integer		UID of the recommendation.
	 * @param		integer		Page ID of the recommendation.
	 * @param		string		Text of the recommendation.
	 * @param		integer		Min value this recommendation is displayed for.
	 * @param		integer		Max value this recommendation is displayed for.
	 */
	function tx_wecassessment_recommendation($uid, $pid, $text, $minValue, $maxValue) {
		$this->_uid = $uid;
		$this->_pid = $pid;
		$this->_text = $text;
		$this->_min_value = $minValue;
		$this->_max_value = $maxValue;
		
		$this->_validationErrors = array();
	}

	/**
	 * Converts a recommendation to an associative array
	 *
	 * @return		Associative array representing the recommendation.
	 */
	function toArray() {
		return array(
			"uid" => $this->getUID(),
			"pid" => $this->getPID(),
			"text" => $this->getText(),
			"min_value" => $this->getMinValue(),
			"max_value" => $this->getMaxValue(),
			"score" => round($this->getScore(), 2),
			"maxScore" => $this->getMaxScore(),
		);
	}
	
	/*************************************************************************
	 *
	 * Default Getters and Setters
	 *
	 ************************************************************************/
	
	/**
	 * Gets the UID of the recommendation.
	 *
	 * @return		integer		The UID of the recommendation.
	 */
	function getUID() { 
		return $this->_uid; 
	}
	
	/**
	 * Sets the UID of the recommendation.
	 *
	 * @param		integer		The UID of the recommendation.
	 * @return		none
	 */
	function setUID($uid) { 
		$this->_uid = $uid; 
	}
	
	/**
	 * Gets the Page ID the recommendation is stored on.
	 *
	 * @return		integer		The Page ID the recommendation is stored on.
	 */
	function getPID() { 
		return $this->_pid; 
	}
	
	/**
	 * Sets the Page ID the recommendation is stored on.
	 *
	 * @param		integer		The Page ID the recommendation is stored on.
	 * @return		none
	 */
	function setPID($pid) { 
		$this->_pid = $pid; 
	}
	
	/**
	 * Gets the text of the recommendation.
	 *
	 * @return		integer		The text of the recommendation.
	 */
	function getText() { 
		return $this->_text; 
	}
	
	/**
	 * Sets the text of the recommendation.
	 *
	 * @param		string		The text of the recommendation.
	 * @return		none
	 */
	function setText($text) { 
		$this->_text = $text; 
	}
	
	
	/**
	 * Gets the minimum value of the recommendation.
	 *
	 * @return		integer		The minimum value of the recommendation.
	 */
	function getMinValue() { 
		return $this->_min_value; 
	}
	
	/**
	 * Sets the minimum value of the recommendation.
	 *
	 * @param		integer		The minimum value of the recommendation.
	 * @return		none
	 */
	function setMinValue($value) { 
		$this->_min_value = $value; 
	}
	
	/**
	 * Gets the maximum value of the recommendation.
	 *
	 * @return		integer		The maximum value of the recommendation.
	 */
	function getMaxValue() { 
		return $this->_max_value; 
	}
	
	/**
	 * Sets the maximum value of the recommendation.
	 *
	 * @param		integer		The maximum value of the recommendation.
	 * @return		none
	 */
	function setMaxValue($value) { 
		$this->_max_value = $value; 
	}
	
	/**
	 * Gets the score for the recommendation.
	 * @return		integer		The score.
	 */
	function getScore() {
		return $this->_score;
	}
	
	/**
	 * Sets the score for the recommendation.
	 * @param		integer		The score.
	 * @return		none
	 */
	function setScore($score) {
		$this->_score = $score;
	}
	
	/**
	 * Gets the maximum possible score for the recommendation.
	 * @return		integer		The score.
	 */
	function getMaxScore() {
		return $this->_maxScore;
	}
	
	/**
	 * Sets the maximum possible score for the recommmendation.
	 * @param		integer		The score.
	 * @return		none
	 */
	function setMaxScore($score) {
		$this->_maxScore = $score;
	}
		
	/*************************************************************************
	 *
	 * Static Functions
	 *
	 ************************************************************************/
	
	/**
	 * Finds a single recommendation by its unique ID.
	 * @param		integer		UID of the recommendation.
	 * @param		boolean		If true, hidden records are included.
	 * @return		object		The recommendation object.
	 */
	function find($uid, $showHidden=false) {
		$table = 'tx_wecassessment_recommendation';
		
		$row = tx_wecassessment_recommendation::getRow($table, $uid, '', $showHidden);
		$recommendation = tx_wecassessment_recommendation::newFromArray($row);

		return $recommendation;
	}
	
	/**
	 * Finds all records on a given PID matching the WHERE clause.
	 * @param		integer		The PID to search in.
	 * @param		string		Custom WHERE clause.
	 * @return		array		Array of recommendation objects.
	 */
	function findAll($pid, $additionalWhere="") {
		$recommendations = array();
		$table = 'tx_wecassessment_recommendation';
		
		$where = tx_wecassessment_recommendation::getWhere($table, $additionalWhere.' AND pid='.$pid);	
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', $table, $where, '', 'min_value');
		
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)) {
			$recommendations[] = tx_wecassessment_recommendation::newFromArray($row);
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($result);
		return $recommendations;
	}
	
	/**
	 * Finds all recommendations of a specific type.
	 * @param		integer		The PID to search in.
	 * @param		string		Custom WHERE clause.
	 * @param		string		Recommendation type.
	 * @return		array		Array of recommendation objects.
	 */
	function findAllWithType($pid, $additionalWhere="1=1", $type) {
		$where = tx_wecassessment_recommendation::combineWhere($additionalWhere, 'type='.$type);
		$recommendations = tx_wecassessment_recommendation::findAll($pid, $where);
		return $recommendations;
	}
	
	/**
	 * @todo 		Unused?
	 */
	function findRecommendationsAndErrors($pid) {
		$recommendations = tx_wecassessment_recommendation::findAll($pid);
		$errors = tx_wecassessment_recommendation::findErrors($pid);
	}
	
	/**
	 * Finds a recommendation by value.
	 * @param		integer		The value we want a recommendation for.
	 * @param		string		Custom WHERE clause.
	 * @return		object		The recommendation object.
	 */
	function findByValue($value, $where='1=1') {
		$table = 'tx_wecassessment_recommendation';
		$where = tx_wecassessment_recommendation::getWhere($table, ' AND min_value<='.$value.' AND max_value>'.$value);
		
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', $table, $where);
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result);
		$GLOBALS['TYPO3_DB']->sql_free_result($result);
		
		if(is_array($row)) {
			$recommendation = &tx_wecassessment_recommendation::newFromArray($row);
		} else {
			$recommendation = null;
		}
		
		return $recommendation;
	}

	/**
	 * Calculate the recommendation to be used.
	 * @param		array		Array of answer objects.
	 * @param		integer		The minimum possible value.
	 * @param		integer		The maximum possible value.
	 * @return		object		The recommendation object.
	 */
	function calculate($answers, $minValue, $maxValue) {
		foreach((array) $answers as $answer) {
			$question = $answer->getQuestion();
			
			$answerTotal += $answer->getWeightedScore();
			$weightTotal += $question->getWeight();
		}
				
		/* @todo		How to deal with weights of 0? */
		if ($weightTotal==0) {
			$weightTotal = 1;
		}
		
		$value = $answerTotal / $weightTotal;
		
		/* @todo 	ugly hack!  If we have a perfect score, back it down a tiny bit so that we get a recommendation */
		if($value == $maxValue) {
			$recommendation = tx_wecassessment_recommendation::findByValue($value-0.01, $category->getUID());
		} else {
			$recommendation = tx_wecassessment_recommendation::findByValue($value, $category->getUID());
		}
		
		if(is_object($recommendation)) {				
			$recommendation->setScore($value);
			$recommendation->setMaxScore($highTotal / $weightTotal);
		}
		
		return $recommendation;
	}
	
	/**
	 * Creates a new recommendation object from an associative array.
	 *
	 * @param		array		Associate array for a recommendation.
	 * @return		object		Answer object.
	 */
	function newFromArray($row) {
		$table = 'tx_wecassessment_recommendation';
		$row = tx_wecassessment_recommendation::processRow($table, $row);
		switch($row['type']) {
			case 0:
				$recommendationClass = 'tx_wecassessment_recommendation_category';
				$recommendation = new $recommendationClass($row['uid'], $row['pid'], $row['text'], $row['min_value'], $row['max_value'], $row['category_id']);
				break;
			case 1:
				$recommendationClass = 'tx_wecassessment_recommendation_question';
				$recommendation = new $recommendationClass($row['uid'], $row['pid'], $row['text'], $row['min_value'], $row['max_value'], $row['question_id']);
				break;
			case 2:
				$recommendationClass = 'tx_wecassessment_recommendation_assessment';
				$recommendation = new $recommendationClass($row['uid'], $row['pid'], $row['text'], $row['min_value'], $row['max_value']);
				break;
			default:
				$recommendationClass = t3lib_div::makeInstanceClassName($table);
				$recommendation = new $recommendationClass($row['uid'], $row['pid'], $row['text'], $row['min_value'], $row['max_value']);
				break;
		}
		
		return $recommendation;
	}
		
	/*************************************************************************
	 *
	 * Validation Functions
	 *
	 ************************************************************************/
	
	
	/**
	 * Performs internal validation of a recommendation, based on a given valid
	 * range.  A recommendation is checked to make sure it falls within this range
	 * and its max value is actually greater than its min value.
	 *
	 * @param	integer		Minimum valid value.
	 * @param	integer		Maximum valid value.
	 * @param	array		Array of error messages if invalid.  Empty array
	 *						if valid.
	 */
	function valid($min, $max) {
		$valid = true;
		
		if (!$this->checkRange()) {
			$this->addError("Maximum recommendation value (".$this->getMaxValue().") is less than minimum recommendation value (".$this->getMinValue().").", $this->getUID());
			$valid = false;
		}
		
		if (!$this->checkLowerBound($min)) {
			$this->addError("Recommendation value is less than the minimum allowed (".$min.").", $this->getUID());
			$valid = false;
		}
		
		if (!$this->checkUpperBound($max)) {
			$this->addError("Recommendation value is greater than the maximum allowed (".$max.").", $this->getUID());
			$valid = false;
		}
		
		return $valid;
		
	}
		
	/*
	 * Perform basic sanity checking to ensure that max value is not smaller
	 * than min value.
	 * @return		boolean		True for pass, false for fail.
	 */
	function checkRange() {
		if($this->getMaxValue() >= $this->getMinValue()) {
			$validRange = true;
		} else {
			$validRange = false;
		}
		
		return $validRange;
	}
	
	/*
	 * Checks if lower bound for this recommendation is valid.
	 * @param		integer		Smallest recommendation value allowed.
	 * @return		boolean		Test result.
	 */
	function checkLowerBound($lowerBound) {
		if($this->getMinValue() >= $lowerBound) {
			$validLowerBound = true;
		} else {
			$validLowerBound = false;
		}
		
		return $validLowerBound;
	}
	
	/*
	 * Checks if the upper bound for this recommendation is valid.
	 * @param		integer		Largest recommendation value allowed.
	 * @return		boolean		Test result.
	 */
	function checkUpperBound($upperBound) {
		if($upperBound >= $this->getMaxValue()) {
			$validUpperBound = true;
		} else {
			$validUpperBound = false;
		}
		
		return $validUpperBound;
	}
	
	/**
	 * Checks if this recommendations is valid relative to another recommendation.
	 * This means that values do not overlap or have a gap.
	 * @param		object		The recommendation to compare against.
	 * @return		boolean
	 */
	function validRelativeTo($previousRecommendation) {
		/* @todo 	What should gap tolerance be set to and where should it be defined? */
		$gapTolerance = 1;
		
		$valid = true;
		$currentUID = $this->getUID();
		$previousUID = $previousRecommendation->getUID();
		
		$previousMax = $previousRecommendation->getMaxValue();
		$currentMin = $this->getMinValue();

		if($previousMax > $currentMin) {
			$this->addError("Recommendation values overlap.", $previousUID, $currentUID);
			$valid = false;
		} else {
			$gap = $currentMin - $previousMax;
			if($gap > $gapTolerance) {
				$this->addError("A gap exists between recommendation values.", $previousUID, $currentUID);
				$valid = false;
			}
		}
	
		return $valid;
	}
	
	function addError($message, $uid1="", $uid2="") {
		$this->_validationErrors[] =  array("message" => $message, "uid1" => $uid1, "uid2" => $uid2);
	}
	
	function getValidationErrors() {
		return $this->_validationErrors;
	}
		
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/model/class.tx_wecassessment_recommendation.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/model/class.tx_wecassessment_recommendation.php']);
}
?>
