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
require_once(t3lib_extMgm::extPath('wec_assessment').'pi1/class.tx_wecassessment_sessiondata.php');

define('USER_ASSESSMENT', 0);
define('ANONYMOUS_ASSESSMENT', 1);

class tx_wecassessment_result extends tx_wecassessment_modelbase {

	var $_uid;
	var $_pid;
	var $_type;
	var $_feUserUID;
	
	var $_questions;
	var $_answers;
	
	/**
	 * Default constructor.
	 * @param		integer		Unique ID of the result.
	 * @param		integer		Page ID where result is stored.
	 * @param		integer		???
	 * @param		array		Array of question objects.
	 * @param		array		Array of answer objects.
	 * @return		none
	 */
	function tx_wecassessment_result($uid, $pid, $type, $feUserUID=0, $questions=null, $answers=null) {
		$this->_uid = $uid;
		$this->_pid = $pid;
		$this->_type = $type;
		$this->_feUserUID = $feUserUID;
		
		$this->_questions = $questions;
		$this->_answers = $answers;
	}
		
	function reset() {
		$this->_uid = 0;
		$this->resetAnswers();
	}
	
	/**
	 * Converts the result object to an associative array.
	 *
	 * @return		array		Associative array representing the current result object.
	 */
	function toArray() {
		return array("uid" => $this->getUID(), "pid" => $this->getPID(), "type" => $this->getType(), "feuser_id" => $this->getFEUserUID());
	}
	
	function newFromArray($row) {
		$table = 'tx_wecassessment_result';
		
		$row = tx_wecassessment_result::processRow($table, $row);
		$resultClass = t3lib_div::makeInstanceClassName($table);
		return new $resultClass($row['uid'], $row['pid'], $row['type'], $row['feuser_id']);			
	}
	
	/**
	 * Saves the result object to a session or to its own database record.
	 *
	 * @return		none
	 */
	function save() {
		/* If every question is answered, save to the db.  Otherwise, to session. */
		if($this->isComplete()) {
			$this->saveToRecord();
		} else {
			$this->saveToSession();
		}
	}
	
	/**
	 * Saves the result object to a database record.
	 *
	 * @return		integer		The UID of the record
	 */
	function saveToRecord() {
		$fields_values = $this->toArray();
		unset($fields_values['uid']);
		
		/* If we have a non-zero UID, update an existing record, otherwise create a new record */
		if($this->getUID()) {
			$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_wecassessment_result', 'uid='.$this->getUID(), $fields_values);
		} else {
			$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_wecassessment_result', $fields_values);
			$this->setUID($GLOBALS['TYPO3_DB']->sql_insert_id());			
		}
		
		$answers = $this->getAnswers();
		foreach($answers as $answer) {
			$answer->setResultUID($this->getUID());
			$answer->save();
		}
		
		/* Blow up session data */
		tx_wecassessment_sessiondata::storeSessionData(null);
		
		return $this->_uid;
	}
	
	/**
	 * Saves the result object to a TYPO3 session by serializing it.
	 *
	 */
	function saveToSession() {
		/* Unset sessions since this info is elsewhere in the database */
		unset($this->_questions);
		tx_wecassessment_sessiondata::storeSessionData($this);
	}
	
	
	/**
	 * Attempts to find the result for the current frontend user.  If one
	 * cannot be found, we can create a new result.
	 *
	 * @param		integer		The page ID that result object is tied to.
	 * @return		object		A result object.
	 */
	function findCurrent($pid) {
		/*
		 * 1.  Check for a result stored in the session.
		 * 2.  Check for a result stored in the database.
		 * 3.  Make a new result.
		 */
		
		if(TYPO3_MODE=="FE") {
			
			/* If we don't have anything in the session, make a new result */
			if(!$result = tx_wecassessment_sessiondata::retrieveSessionData()) {
				if ($GLOBALS['TSFE']->fe_user->user['uid']) {
					$type = USER_ASSESSMENT;
 					$feuser_id = $GLOBALS['TSFE']->fe_user->user['uid'];
				} else {
					$type = ANONYMOUS_ASSESSMENT;
					$feuser_id = $GLOBALS['TSFE']->fe_user->id;
				}
				
				if(!$result = tx_wecassessment_result::findInDB($feuser_id)) {
					$uid = 0;			
					$resultClass = t3lib_div::makeInstanceClassName('tx_wecassessment_result');
					$result = new $resultClass($uid, $pid, $type, $feuser_id);	
				}
			}
		} else {
			$result = null;
		}
		
		return $result;
	}
	
	
	function findInDB($feUserUID) {
		$table = 'tx_wecassessment_result';		
		$where = tx_wecassessment_category::getWhere($table, 'feuser_id="'.$feUserUID.'"');
		
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', $table, $where);		
		if($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)) {
			$result = tx_wecassessment_result::newFromArray($row);
			$result->getQuestions();
			$result->getAnswers(true);
			/**
			 * @todo 	Wy does freeing result generate error?
			 * $GLOBALS['TYPO3_DB']->sql_free_result($result);
			 */
		} else {
			$result = null;
		}
		
		return $result;
	}
	
	/**
	 * Finds the result with the given unique ID in the database.
	 *
	 * @param		integer		Unique ID to find.
	 * @return		object		The result object.
	 */
	function find($uid) {
		$table = 'tx_wecassessment_result';
		$row = tx_wecassessment_result::getRow($table, $uid);

		$result = tx_wecassessment_result::newFromArray($row);
		return $result;		
	}
	
	/**
	 * Get overall list of categories
	 * Get answers that live in each category
	 * Get a weighted value
	 * Find the correct response
	 *
	 * @todo	Must support categories
	 */
	function getResponses() {
		
		$answers = $this->getAnswers();	
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
		$response = tx_wecassessment_response::findByValue($value);
		
		return $response;
	}
	
	/*************************************************************************
	 *
	 * Default Getters and Setters
	 *
	 ************************************************************************/
	
	/**
	 * Gets the UID of the result.
	 *
	 * @return		integer		The UID of the result.
	 */
	function getUID() { 
		return $this->_uid; 
	}

	/**
	 * Sets the UID of the result.
	 *
	 * @param		integer		The UID of the result.
	 * @return		none
	 */
	function setUID($uid) {
		$this->_uid = $uid;
	}

	/**
	 * Gets the Page ID where the result is stored.
	 *
	 * @return		integer		The page ID where the result is stored.
	 */
	function getPID() { 
		return $this->_pid; 
	}

	/**
	 * Sets the Page ID where the result is stored.
	 *
	 * @param		integer		The page ID where the result is stored.
	 * @return		none
	 */
	function setPID($pid) {
		$this->_pid = $pid;
	}

	/**
	 * Gets the type of the result, user or anonymous.
	 *
	 * @return		integer		Type of the result.
	 */
	function getType() { 
		return $this->_type; 
	}
	
	/**
	 * Sets the type of the result.
	 *
	 * @param		integer		Type of the result.
	 * @return		none
	 */
	function setType($type) {
		$this->_type = $type;
	}
	
	/**
	 * Gets the user id for the assessment.  For anonymous users, this is a hash.
	 * For registered users, this is the feuser_id.
	 *
	 * @return		mixed		User ID.
	 */
	function getFEUserUID() { 
		return $this->_feUserUID; 
	}
	
	/**
	 * Sets teh user id for the assessment.
	 *
	 * @param		mixed		User ID.
	 * @return		none
	 */
	function setFEUserUID($feUserUID) {
		$this->_feUserUID = $feUserUID;
	}
	
	function getUsername() {
		if($this->getType() == 0) {
			$row = $this->getRow('fe_users', $this->getFEUserUID());
			$username = $row['username'];
		} else {
			$username = "Anonymous Visitor: ".$this->getFEUserUID();
		}
		
		return $username;
	}
	
	/**
	 * Gets all questions stored on the current page.
	 *
	 * @return		array		Array of questions.
	 */	
	function getQuestions() {
		if(!$this->_questions) {
			$this->_questions = tx_wecassessment_question::findAll($this->getPID());
		} 
		
		return $this->_questions;
	}
	
	/**
	 * Gets the questions that appear for a specific page number.
	 *
	 * @param		integer		The page number.
	 * @param		integer		The number of questions per page.
	 * @return		array		Array of questions.
	 * @todo		Can we use the existing question array rather than querying the db again?
	 */
	function getQuestionsInPage($pageNumber, $questionsPerPage) {
		return tx_wecassessment_question::findInPage($this->getPID(), $pageNumber, $questionsPerPage);
	}
	
	/**
	 * Gets all the answers that are part of this result.
	 *
	 * @return		array		Array of answers.
	 */
	function getAnswers($force=false) {
		/* If we don't have answers and we know the assessment is complete, search the database */
		if((!$this->_answers and $this->isComplete()) or $force) {
			$this->_answers = tx_wecassessment_answer::findInResult($this->getUID());
		}
		
		return $this->_answers;		
	}
	
	/**
	 * Resets the answers array, effectively clearing out all the current answers.
	 *
	 * @return		none
	 */
	function resetAnswers() {
		unset($this->_answers);
		$this->_answers = array();
		
	}
	
	/**
	 * Gets all the answers for one category.
	 *
	 * @param		object		The category.
	 * @return		array		The answers within the category.
	 */
	function getAnswersForCategory($category) {
		$filteredAnswers = array();
		$categoryUID = $category->getUID();
		$answers = $this->getAnswers();
		
		foreach($answers as $answer) {
			if($answer->getCategoryUID() == $categoryUID) {
				$filteredAnswers[] = $answer;
			}
		}
		
		return $filteredAnswers;
	}
	
	/**
	 * Checks if a result is complete by iterating over every question and
	 * making sure it has an answer.
	 * @todo		Is there a better algorithm that we can use here?
	 * 
	 * @return		True/false whether result is complete or not.
	 */
	function isComplete() {
		$questions = $this->getQuestions();

		foreach($questions as $question) {
			if(!$question->hasAnswer($this->_answers)) {
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * Finds the answer, if available, for the specified question.  If no answer
	 * is available, then return false.
	 *
	 * @param		object		The question to be answered.
	 * @return		object		The answer to the question.
	 */	
	function lookUpAnswer($question) {
		if(is_array($this->_answers)) {
			foreach($this->_answers as $answer) {
				if($answer->getQuestionUID() == $question->getUID()) {
					return $answer;
				}
			}
		}
		
		return false;
	}
	
	
	/**
	 * Adds answers from post data.
	 *
	 * @param		array		Post data.
	 * @return		none
	 */
	function addAnswersFromPost($postedAnswers) {
		foreach($postedAnswers as $questionUID => $value) {
			$row = array(
				"uid" => 0,
				"pid" => $this->getPID(),
				"value" => intval($value),
				"question_id" => $questionUID,
				"result_id" => $this->getUID(),
				);
			/* @todo	Figure out a better array index. */
			$this->_answers[$questionUID] = tx_wecassessment_answer::newFromArray($row);
		}
	}
	
	/**
	 * Checks if the current result has any answers.
	 *
	 * @return		boolean		True/False whether result has answers.
	 */
	function hasAnswers() {
		$answers = $this->getAnswers();
		if(sizeof($answers) > 0) {
			$hasAnswers = true;
		} else {
			$hasAnswers = false;
		}
		
		return $hasAnswers;
	}
	
	/**
	 * Gets the categories for the current result.
	 *
	 * @return		array		Array of category objects contained in the current result.
	 */
	function getCategories() {
		$answers = $this->getAnswers();
		$categories = array();
		
		foreach($answers as $answer) {
			$question = $answer->getQuestion();
			$category = $question->getCategory();
			$categories[$category->getUID()] = $category;
		}
		
		return $categories;
	}
	
	/**
	 * Gets the label for the current record.
	 *
	 * @return		string
	 */
	function getLabel() {
		return $this->getUsername();
	}
	
	
}
?>