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
require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_category.php');

class tx_wecassessment_response extends tx_wecassessment_modelbase {
	
	var $_uid;
	var $_text;
	var $_min_value;
	var $_max_value;
	var $_categoryUID;
	var $_category;
	
	var $_validationErrors;
	
	
	/**
	 * Default constructor.
	 *
	 * @param		integer		UID of the response.
	 * @param		integer		Page ID of the response.
	 * @param		string		Text of the response.
	 * @param		integer		Min value this response is displayed for.
	 * @param		integer		Max value this response is displayed for.
	 * @param		integer		Category UID this response belongs to.
	 */
	function tx_wecassessment_response($uid, $pid, $text, $minValue, $maxValue, $categoryUID) {
		$this->_uid = $uid;
		$this->_pid = $pid;
		$this->_text = $text;
		$this->_min_value = $minValue;
		$this->_max_value = $maxValue;
		$this->_categoryUID = $categoryUID;
		
		$this->_validationErrors = array();
	}

	/**
	 * Converts a response to an associative array
	 *
	 * @todo		Decide what to do with category.  We need it in the cObj but not for SQL related stuff.
	 * @return		Associative array representing the response.
	 */
	function toArray() {
		$category = $this->getCategory();
		
		return array(
			"uid" => $this->getUID(),
			"pid" => $this->getPID(),
			"text" => $this->getText(),
			"min_value" => $this->getMinValue(),
			"max_value" => $this->getMaxValue(),
			"category" => $category ? $category->getTitle() : "",
		);
	}
	
	/*************************************************************************
	 *
	 * Default Getters and Setters
	 *
	 ************************************************************************/
	
	/**
	 * Gets the UID of the response.
	 *
	 * @return		integer		The UID of the response.
	 */
	function getUID() { 
		return $this->_uid; 
	}
	
	/**
	 * Sets the UID of the response.
	 *
	 * @param		integer		The UID of the response.
	 * @return		none
	 */
	function setUID($uid) { 
		$this->_uid = $uid; 
	}
	
	/**
	 * Gets the Page ID the response is stored on.
	 *
	 * @return		integer		The Page ID the response is stored on.
	 */
	function getPID() { 
		return $this->_pid; 
	}
	
	/**
	 * Sets the Page ID the response is stored on.
	 *
	 * @param		integer		The Page ID the response is stored on.
	 * @return		none
	 */
	function setPID($pid) { 
		$this->_pid = $pid; 
	}
	
	/**
	 * Gets the text of the response.
	 *
	 * @return		integer		The text of the response.
	 */
	function getText() { 
		return $this->_text; 
	}
	
	/**
	 * Sets the text of the response.
	 *
	 * @param		string		The text of the response.
	 * @return		none
	 */
	function setText($text) { 
		$this->_text = $text; 
	}
	
	
	/**
	 * Gets the minimum value of the response.
	 *
	 * @return		integer		The minimum value of the response.
	 */
	function getMinValue() { 
		return $this->_min_value; 
	}
	
	/**
	 * Sets the minimum value of the response.
	 *
	 * @param		integer		The minimum value of the response.
	 * @return		none
	 */
	function setMinValue($value) { 
		$this->_min_value = $value; 
	}
	
	/**
	 * Gets the maximum value of the response.
	 *
	 * @return		integer		The maximum value of the response.
	 */
	function getMaxValue() { 
		return $this->_max_value; 
	}
	
	/**
	 * Sets the maximum value of the response.
	 *
	 * @param		integer		The maximum value of the response.
	 * @return		none
	 */
	function setMaxValue($value) { 
		$this->_max_value = $value; 
	}
	
	/**
	 * Gets the UID of the category this response belongs to.
	 *
	 * @return		integer		UID of the category.
	 */
	function getCategoryUID() { 
		return $this->_categoryUID; 
	}
	
	/**
	 * Sets the UID of the category this response belongs to. Also, unsets
	 * $this->_category and waits for it be reloaded as needed.
	 *
	 * @param		integer		UID of the category.
	 * @return		none
	 */
	function setCategoryUID($uid) { 
		$this->_categoryUID = $uid; 
		unset($this->_category); 
	}
	
	/**
	 * Gets the category this response belongs to.
	 *
	 * @return		object		The category.
	 */
	function getCategory() {
		if(!$this->_category and $this->getCategoryUID()) {
			$this->_category = tx_wecassessment_category::find($this->getCategoryUID());
		}
		
		return $this->_category;
	}
	
	/**
	 * Sets the category this response belongs to.  Also, resets $this->_categoryUID
	 * to the updated UID.
	 *
	 * @param		object		The category.
	 * @return		none
	 */
	function setCategory($category) { 
		$this->_category = $category; 
		$this->_categoryUID = $category->getUID(); 
	}
	
	/*************************************************************************
	 *
	 * Static Functions
	 *
	 ************************************************************************/
	
	function find($uid) {
		$table = 'tx_wecassessment_response';
		
		$row = tx_wecassessment_response::getRow($table, $uid);
		$response = tx_wecassessment_response::newFromArray($row);

		return $response;
	}
	
	function findAll($pid, $additionalWhere="") {
		$responses = array();
		$table = 'tx_wecassessment_response';
		
		$where = tx_wecassessment_response::getWhere($table, $additionalWhere.' AND pid='.$pid);	
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', $table, $where, '', 'min_value');
		
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)) {
			$responses[] = tx_wecassessment_response::newFromArray($row);
		}
		
		return $responses;
	}
	
	function findAllInCategory($pid, $category_id, $additionalWhere="") {
		$responses = array();
		$table = 'tx_wecassessment_response';
		
		$where = tx_wecassessment_response::combineWhere($additionalWhere, 'pid='.$pid.' AND category_id='.$category_id);
		$where = tx_wecassessment_response::getWhere($table, $where);
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', $table, $where, '', 'min_value');
		
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)) {
			$responses[] = tx_wecassessment_response::newFromArray($row);
		}
		
		return $responses;
	} 
	
	function findResponsesAndErrors($pid) {
		$responses = tx_wecassessment_response::findAll($pid);
		$errors = tx_wecassessment_response::findErrors($pid);
		
		
	}
	

	
	
	function findByValue($value, $category_id) {
		$table = 'tx_wecassessment_response';
		
		$where = tx_wecassessment_response::getWhere($table, 'category_id='.$category_id.' AND min_value<='.$value.' AND max_value>'.$value);
		
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', $table, $where);
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result);
		
		return tx_wecassessment_response::newFromArray($row);
	}

	function calculate($category, $answers) {
		foreach($answers as $answer) {
			$question = $answer->getQuestion();
			
			$answerTotal += $answer->getWeightedValue();
			$weightTotal += $question->getWeight();
		}
		
		/* @todo		How to deal with weights of 0? */
		if ($weightTotal==0) {
			$weightTotal = 1;
		}
		
		$value = $answerTotal / $weightTotal;		
		$response = tx_wecassessment_response::findByValue($value, $category->getUID());
		return $response;
	}
	
	/**
	 * Creates a new response object from an associative array.
	 *
	 * @param		array		Associate array for a response.
	 * @return		object		Answer object.
	 */
	function newFromArray($row) {
		$table = 'tx_wecassessment_response';
		$row = tx_wecassessment_response::processRow($table, $row);
		$responseClass = t3lib_div::makeInstanceClassName($table);
		return new $responseClass($row['uid'], $row['pid'], $row['text'], $row['min_value'], $row['max_value'], $row['category_id']);
	}
		
	/*************************************************************************
	 *
	 * Validation Functions
	 *
	 ************************************************************************/
	
	
	/**
	 * Performs internal validation of a response, based on a given valid
	 * range.  A response is checked to make sure it falls within this range
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
			$this->addError("Maximum response value (".$this->getMaxValue().") is less than minimum response value (".$this->getMinValue().").", $this->getUID());
			$valid = false;
		}
		
		if (!$this->checkLowerBound($min)) {
			$this->addError("Response value is less than the minimum allowed (".$min.").", $this->getUID());
			$valid = false;
		}
		
		if (!$this->checkUpperBound($max)) {
			$this->addError("Response value is greater than the maximum allowed (".$max.").", $this->getUID());
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
	 * Checks if lower bound for this response is valid.
	 * @param		integer		Smallest response value allowed.
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
	 * Checks if the upper bound for this response is valid.
	 * @param		integer		Largest response value allowed.
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
	
	function validRelativeTo($previousResponse) {
		/* @todo 	What should gap tolerance be set to and where should it be defined? */
		$gapTolerance = 1;
		
		$valid = true;
		$currentUID = $this->getUID();
		$previousUID = $previousResponse->getUID();
		
		$previousMax = $previousResponse->getMaxValue();
		$currentMin = $this->getMinValue();
		
		if($previousMax > $currentMin) {
			$this->addError("Response values overlap.", $previousUID, $currentUID);
			$valid = false;
		} else {
			$gap = $currentMin - $previousMax;
			if($gap > $gapTolerance) {
				$this->addError("A gap exists between response values.", $previousUID, $currentUID);
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

?>