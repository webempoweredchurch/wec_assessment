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
require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_question.php');
require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_result.php');

/**
 * Data model for Assessment Answers.
 *
 * @author	Web-Empowered Church Team <assessment@webempoweredchurch.org>
 * @package TYPO3
 * @subpackage tx_wecassessment
 */
class tx_wecassessment_answer extends tx_wecassessment_modelbase {

	var $_uid;
	var $_pid;
	var $_value;
	var $_questionUID;
	var $_question;
	var $_resultUID;
	var $_result;
	
	/**
	 * Default constructor.
	 * @param		integer		Unique ID of the answer.
	 * @param		integer		Page ID where the answer is stored.
	 * @param		integer		Value of the answer.
	 * @param		integer		Unique ID of the question this object answers.
	 * @param		integer		Unique ID of the overall result this answer belongs to.
	 * @return		none
	  */
	function tx_wecassessment_answer($uid, $pid, $value, $questionUID, $resultUID) {
		$this->_uid = $uid;
		$this->_pid = $pid;
		$this->_value = $value;
		$this->_questionUID = $questionUID;
		$this->_resultUID = $resultUID;
	}
	
	/**
	 * Converts an answer object to an associative array for use as cObject data.
	 * @return		array		Associate array representing current answer.
	 */
	function toArray() {
		return array(
			"uid" => $this->getUID(), 
			"pid" => $this->getPID(), 
			"value" => $this->getValue(), 
			"question_id" => $this->getQuestionUID(), 
			"result_id" => $this->getResultUID());
	}
	
	
	/** 
	 * Saves an answer to the database.
	 * @return		integer		The unique ID of the answer.
	 * @todo 		Add tstamp, cruser_id, etc.
	 */
	function save() {
		$fields_values = $this->toArray();
		unset($fields_values['uid']);
		
		/* If we have a uid, update an db existing record.  If not, create a new db record. */
		if($this->getUID()) {
			$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_wecassessment_answer', 'uid='.$this->getUID(), $fields_values);
		} else {
			$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_wecassessment_answer', $fields_values);
			$this->setUID($GLOBALS['TYPO3_DB']->sql_insert_id());
		}
		
		return $this->getUID();
	}
	
	/**
	 * Returns the weighted value of this answer.
	 * @return		integer		The value of this answer after weighting.
	 * @todo		What's the error return value of we don't get a question?
	 */
	function getWeightedScore() {
		$question = $this->getQuestion();
		
		if(is_a($question, "tx_wecassessment_question")) {
			/* @todo  Does getWeight() have problems? */
			$weight = $question->getWeight();
			$weightedValue = $weight * $this->getValue();
		} else {
			$weightedValue = 0;
		}
		return $weightedValue;
	}
	
	function getWeight() {
		$question = $this->getQuestion();
		return $question->getWeight();
	}
	
	/**
	 * Gets the UID of the category this answer belongs to.
	 * @return		integer		The UID of the category this answer belongs to.
	 */
	function getCategoryUID() {
		$question = $this->getQuestion();
		return $question->getCategoryUID();
	}
	
	
	/*************************************************************************
	 *
	 * Default getters and setters.
	 *
	 ************************************************************************/
	
	/**
	 * Gets the UID of the answer.
	 * 
	 * @return		integer		The UID of the answer.
	 */
	function getUID() { 
		return $this->_uid;
	}
	
	/**
	 * Sets the UID of of the answer.
	 *
	 * @param		integer		The UID of the answer.
	 * @return		none
	 */
	function setUID($uid) { 
		$this->_uid = $uid; 
	}
	
	
	/**
	 * Gets the Page ID that the answer resides on.
	 * 
	 * @return		integer		The PID of the answer.
	 */
	function getPID() { 
		return $this->_pid; 
	}
	
	/**
	 * Sets the Page ID that the answer resides on.
	 *
	 * @param		integer		The PID of the answer.
	 * @return		none
	 */		
	function setPID($pid) { 
		$this->_pid = $pid; 
	}
	
	
	/**
	 * Gets the value of the answer.
	 *
	 * @return		integer		The value of the answer.
	 */
	function getValue() { 
		return $this->_value; 
	}
	
	function getScore() {
		return $this->getValue();
	}
	
	/**
	 * Sets the value of the answer.
	 *
	 * @param		integer		The value of the answer.
	 * @return		none
	 */
	function setValue($value) { 
		$this->_value = $value; 
	}
	
	
	/**
	 * Gets the UID of the question this answers.
	 *
	 * @return		integer		The UID of the question.
	 */
	function getQuestionUID() { 
		return $this->_questionUID; 
	}
	
	/**
	 * Sets the UID of the question this answers.  Also clears out the current
	 * value in $this->_question and waits for it to be reloaded if needed.
	 *
	 * @param		integer		The UID of the question.
	 * @return		none
	 */
	function setQuestionUID($questionUID) { 
		$this->_questionUID = $questionUID; 
		unset($this->_question);
	}
	
	
	/**
	 * Gets the UID of the result this belongs to.
	 *
	 * @return		integer		The UID of the result this belongs to.
	 */
	function getResultUID() { 
		return $this->_resultUID; 
	}
	
	/*
	 * Sets the UID of the result containing this answer.  Also clears out the
	 * current value in $this->_result and waits for it to be reloaded if needed.
	 *
	 * @param		integer		Unique ID of the result containing this answer.
	 * @return		none
	 */
	function setResultUID($uid) {
		$this->_resultUID = $uid;
		unset($this->_result);
	}
	
	/**
	 * Gets the question associated with this answer.
	 *
	 * @return		object		The question associated with this answer.
	 */
	function getQuestion() {
		if(!$this->_question) {
			$this->_question = tx_wecassessment_question::find($this->getQuestionUID());
		}
		
		return $this->_question;
	}
	
	/**
	 * Sets the question associated with this answer.  Also updates the value
	 * for $this->_questionUID.
	 * 
	 * @param		object		The question associated with this answer.
	 * @return		none
	 */
	function setQuestion($question) { 
		$this->_question = $question; 
		$this->_questionUID = $question->getUID();
	}
	
	
	/**
	 * Gets the overall result set associated with this answer.
	 *
	 * @return		object		The result set that contains this answer.
	 */
	function getResult() {
		if(!$this->_result) {
			$this->_result = tx_wecassessment_result::find($this->_resultUID);
		}
		
		return $this->_result;
	}
	
	/**
	 * Sets the result associated with this answer.  Also updates the value
	 * for $this->_resultUID.
	 *
	 * @param		object		The result associated with this answer.
	 * @return		none
	 */
	function setResult($result) { 
		$this->_result = $result; 
		$this->_resultUID = $result->getUID();
	}
	
	/**
	 * Gets the label for the current record.
	 * 
	 * @return		string
	 */
	function getLabel() {
		$question = $this->getQuestion();
		
		return $question->getLabel();
	}
	
	
	/*************************************************************************
	 *
	 * Static Methods
	 *
	 ************************************************************************/
	
	/**
	 * Finds the answer with the given unique ID.
	 *
	 * @param		integer		Unique ID to find.
	 * @return		object		The answer object.
	 */
	function find($uid, $showHidden=false) {
		$table = 'tx_wecassessment_answer';
		$row = tx_wecassessment_answer::getRow($table, $uid, '', $showHidden);

		$answer = tx_wecassessment_answer::newFromArray($row);
		return $answer;		
	}
	
	/** 
	 * Finds all answers on a given page.
	 *
	 * @param		integer		Page ID where the answer is stored.
	 * @param		string		SQL WHERE clause for additional filtering.
	 * @return		array		Array of answers matching search criteria.
	 * @todo	Figure out a better array key.
	 */
	function findAll($pid, $additionalWhere='', $questionAsKey=true) {
		$answers = array();
		$table = 'tx_wecassessment_answer';
		
		$where = tx_wecassessment_answer::getWhere($table, $additionalWhere.' AND pid='.$pid);
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', $table, $where);		
		
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)) {
			if($questionAsKey) {
				$answers[$row['question_id']] = tx_wecassessment_answer::newFromArray($row);
			} else {
				$answers[] = tx_wecassessment_answer::newFromArray($row);
				
			}
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($result);

		return $answers;
	}
	
	/**
	 * Finds all answers in a given result.
	 *
	 * @param		integer		The UID of the result to search in.
	 * @return		array		Array of answers within the given result.
	 */
	function findInResult($resultUID) {
		$answers = array();
		$table = 'tx_wecassessment_answer';

		$where = tx_wecassessment_answer::getWhere($table, 'result_id='.$resultUID);
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', $table, $where);
				
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)) {
			$answers[$row['question_id']] = tx_wecassessment_answer::newFromArray($row);
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($result);

		return $answers;	
	}
	
	/**
	 * Creates a new answer object from an associative array.
	 *
	 * @param		array		Associative array for an answer.
	 * @return		object		Answer object.
	 */
	function newFromArray($row) {
		$table = 'tx_wecassessment_answer';
		$row = tx_wecassessment_answer::processRow($table, $row);
		$answerClass = t3lib_div::makeInstanceClassName($table);
		return new $answerClass($row['uid'], $row['pid'], $row['value'], $row['question_id'], $row['result_id']);
	}
	
	
	/**
	 * Calculates the recommendation that is generated for the current answer.
	 * @return		object		The recommendation object.
	 */
	function calculateRecommendation() {
		$weightedValue = $this->getWeightedScore();
		$parentTable = 'tx_wecassessment_question';
		$parentID = $this->getQuestionUID();
		
		return tx_wecassessment_recommendation::findByValue($weightedScore, $parentTable, $parentID);
	}
	
		
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/model/class.tx_wecassessment_answer.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/model/class.tx_wecassessment_answer.php']);
}


?>