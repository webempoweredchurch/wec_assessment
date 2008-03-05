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
require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_recommendation_question.php');
require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_category.php');

/**
 * Data models for Assessment Questions.
 *
 * @author	Web-Empowered Church Team <assessment@webempoweredchurch.org>
 * @package TYPO3
 * @subpackage tx_wecassessment
 */
class tx_wecassessment_question extends tx_wecasssessment_recommendationcontainer {

	var $_uid;
	var $_pid;
	var $_sorting;
	var $_text;
	var $_categoryUID;
	var $_weight;
	var $_allAnswers;
	var $_allRecommendations;
	
	var $_recommendationClass = 'tx_wecassessment_recommendation_question';
	
	/**
	 * Default constructor.
	 *
	 * @param		integer		Unique ID of the question.
	 * @param		integer		Page ID where the question is stored.
	 * @param		integer		Sorting order relative to other questions on the same page.
	 * @param		string		Text of the question.
	 * @param		integer		Category ID that this question belongs to.
	 * @param		integer		Weight of the question.  Score = Answer Value * Question Weight.
	 * @return		none
	 */
	function tx_wecassessment_question($uid, $pid, $sorting, $text, $categoryUID, $weight) {
		$this->_uid = $uid;
		$this->_pid = $pid;
		$this->_sorting = $sorting;
		$this->_text = $text;
		$this->_categoryUID = $categoryUID;
		$this->_weight = $weight;
	}
	
	/**
	 * Converts a question object into an associative array.
	 *
	 * @return		array		Associative array representing the current question object.
	 */
	function toArray() {
		return array("uid" => $this->getUID(), "pid" => $this->getPID(), "sorting" => $this->getSorting(), "text" => $this->getText(), "category_id" => $this->getCategoryUID(), "weight" => $this->getWeight());
	}
	
	/**
	 * Calculates the recommendation for the question.
	 * @param		integer		The score for the question.
	 * @return		object		The recommendation object.
	 */
	function calculateRecommendation($score) {
		return tx_wecassessment_recommendation_question::findByScore($score, $this->getUID());
	}
	
	/**
	 * Gets the label for question.
	 * @return		string		The label.
	 */
	function getLabel() {
		return $this->getText();
	}

	/*************************************************************************
	 *
	 * Generic getters and setters
	 *
	 ************************************************************************/
	
	/**
	 * Gets the UID of the current question.
	 *
	 * @return		integer		UID of the current question.
	 */	
	function getUID() { 
		return $this->_uid; 
	}
	
	/**
	 * Sets the UID of the current question.
	 *
	 * @param		integer		UID of the current question.
	 * @return		none
	 */
	function setUID($uid) { 
		$this->_uid = $uid; 
	}
	
	
	/**
	 * Gets the PID that the question is stored on.
	 *
	 * @return		integer		The PID that the question is stored on.
	 */
	function getPID() { 
		return $this->_pid; 
	}
	
	/**
	 * Sets the PID tha the question is stored on.
	 *
	 * @param		integer		The PID that the question is stored on.
	 * @return		none
	 */
	function setPID($pid) { 
		$this->_pid = $pid; 
	}
	
	/**
	 * Gets the sorting order.
	 *
	 * @return		integer		The sorting order.
	 */
	function getSorting() { 
		return $this->_sorting; 
	}	
	
	/**
	 * Sets the sorting order.
	 * @todo Do we need a better function to sort questions relative to one another?
	 *
	 * @return		integer		The sorting oder.
	 */
	function setSorting($sorting) { 
		$this->_sorting = $sorting; 
	}
	
	/**
	 * Gets the question text.
	 *
	 * @return		string		The text of the question.
	 */
	function getText() { 
		return $this->_text; 
	}
	
	/**
	 * Sets the question text.
	 * 
	 * @param		string		The text of the question.
	 * @return		none
	 */
	function setText($text) { 
		$this->_text = $text; 
	}
	
	/**
	 * Gets the UID of the category this question belongs to.
	 *
	 * @return		integer		UID of the category.
	 */
	function getCategoryUID() { 
		return $this->_categoryUID; 
	}
	
	/**
	 * Sets the UID of the category this question belongs to.  Also, unsets
	 * $this->_category and waits for it to be reloaded as needed.
	 *
	 * @param		integer		The category UID.
	 * @return		none
	 */
	function setCategoryUID($categoryUID) { 
		$this->_categoryUID = $categoryUID; 
		unset($this->_category); 
	}
	
	/**
	 * Gets the weight of the question.
	 *
	 * @return		integer		The weight of the question.
	 */
	function getWeight() { 
		return $this->_weight; 
	}
	
	/**
	 * Sets the weight of the question.
	 *
	 * @param		integer		The weight of the question.
	 * @return		none
	 */
	function setWeight($weight) { 
		$this->_weight = $weight; 
	}
	
	
	/**
	 * Gets the category of the question.
	 *
	 * @return		object		The category of the question.
	 */
	function getCategory() {
		if(!$this->_category and $this->getCategoryUID()) {
			$this->_category = tx_wecassessment_category::find($this->getCategoryUID());
		}
		
		return $this->_category;
	}
	
	
	/**
	 * Sets the category of the question.
	 *
	 * @param		object		The category of the question.
	 * @return		none
	 */
	function setCategory($category) { 
		$this->_category = $category;
		$this->_categoryUID = $category->getUID(); 
	}
	
	
	/**
	 *  Checks if a question is within the given category UID.
	 *
	 * @param		integer		The UID of the category to begin searching within.
	 * @return		boolean		True/false whether question is within the category.
	 */
	function inCategory($categoryUID) {
		$currentCategory = $this->getCategory();
		if($currentCategory->getUID() == $categoryUID) {
			$inCategory = true;
		} else {
			$inCategory = $currentCategory->inCategory($categoryUID);
		}
		
		return $inCategory;
		
	}
	
	/**
	 * Gets the recommendations within the current category.
	 * 
	 * @return		array		The array for recommendations belonging to the current category.
	 * @todo		Order recommendations based on min value.
	 */
	function getRecommendations() {
		if(!$this->_recommendations) {
			$this->_recommendations = tx_wecassessment_recommendation_question::findAllInQuestion($this->getPID(), $this->getUID());
		}
		return $this->_recommendations;
		
	}
	
	/**
	 * Adds a single recommendation to the array of recommendations in this category.
	 * @param		object		The new recommendation object.
	 * @return		none
	 */
	function addRecommendation($recommendation) {
		$recommendation->setCategoryUID($this->getUID());
		$this->_recommendations[] = $recommendation;
	}
	
	
	/**
	 * Checks whether this questions has an answer 
	 * @todo		Optimize, and probably move this up to the result set.
	 *
	 * @param		array		Array of answer objects.
	 * @return		boolean		True if the question has an answer.  False otherwise.
	 */
	function hasAnswer($answers) {
		
		if(is_array($answers)) {
			foreach($answers as $answer) {
				if ($answer->getQuestionUID() == $this->getUID()) {
					return true;
				}
			}
		}
		
		return false;		
	}
	
	/**
	 * Calculates the average score for this question.
	 * @return		integer		The average score
	 */
	function getAverageAnswer() {
		$allAnswers = $this->getAllAnswers();
		if(is_array($allAnswers)) {
			foreach($allAnswers as $answer) {
				$value += $answer->getValue();
			}
			
			if($totalAnswers = $this->getTotalAnswers()) {
				$average = $value / $totalAnswers;	
			} else {
				$average = 0;
			}
			
		}
		
		return round($average, 2);
	}
	
	/**
	 * Gets all answers for the current question.
	 * @return		array		Array of answer objects.
	 */
	function getAllAnswers() {
		if(!$this->_allAnswers) {
			$this->_allAnswers = &tx_wecassessment_answer::findAll($this->getPID(), 'question_id='.$this->getUID(), false);
		}
		
		return $this->_allAnswers;
	}
	
	/**
	 * Gets the total number of answers for the current question.
	 * @return		integer		The total number of answers for the current question.
	 */
	function getTotalAnswers() {
		return count($this->getAllAnswers());
	}
	
	
	/**
	 * Gets all recommendations for the current question.
	 * @return		array		Array of recommendation objects.
	 */
	function getAllRecommendations() {
		if(!$this->_allRecommendations) {
			$this->_allRecommendations = &tx_wecassessment_recommendation::findAll($this->getPID(), 'question_id='.$this->getUID(), false);
		}
		
		return $this->_allRecommendations;
	}
	
	/**
	 * Gets the single calculated recommendation based on the score.
	 * @param		score		The score for the question.
	 * @return		object		The recommendation object.
	 */
	function getCalculatedRecommendation($score) {
		$weightedScore = $this->getWeight() * $score;
		return tx_wecassessment_recommendation::findByValue($weightedScore, 'question_id='.$category_id);
	}
	
	/*************************************************************************
	 *
	 * Static Methods
	 *
	 ************************************************************************/
	
	/*
	 * Finds a single question by its unique ID.
	 * @param		integer		Unique ID of the question.
	 * @return		object		The question object.
	 */
	function find($uid, $showHidden=false) {
		$table = 'tx_wecassessment_question';
		$row = tx_wecassessment_question::getRow($table, $uid, '', $showHidden);
		$question = tx_wecassessment_question::newFromArray($row);
		
		return $question;	
	}
	
	/*
	 * Finds questions on a given page.
	 * @param		integer		Page ID where questions are stored.
	 * @param		string		SQL WHERE clause for additional filtering.
	 * @return		array		Array of question objects.
	 */
	function findAll($pid, $additionalWhere='', $grouping='', $sorting='sorting', $randomize=0) {
		$questions = array();
		$table = 'tx_wecassessment_question';
		
		$where = tx_wecassessment_category::combineWhere($additionalWhere, 'pid='.$pid);
		$where = tx_wecassessment_question::getWhere($table, $where);
		
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', $table, $where, $grouping, $sorting);
		
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)) {
			$questions[] = tx_wecassessment_question::newFromArray($row);
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($result);
		
		if($randomize) {
			shuffle($questions);
		}
		
		return $questions;
	}
	
	/*
	 * Finds questions with paging enabled.
	 * @param		integer		The page ID where the questions are stored.
	 * @param		integer		The page number to be displayed.
	 * @param		integer		The number of questions to show per page.
	 * @todo		Maybe this fits outside the model somewhere?
	 */
	function findInPage($pid, $pageNumber, $questionsPerPage, $grouping='', $sorting='sorting', $randomize=0) {
		$questions = array();
		$table = 'tx_wecassessment_question';
		
		$startQuestion = ($pageNumber-1) * $questionsPerPage;
		
		$where = tx_wecassessment_category::combineWhere($additionalWhere, 'pid='.$pid);
		$where = tx_wecassessment_question::getWhere($table, $where);
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', $table, $where, $grouping, $sorting, $startQuestion.','.$questionsPerPage);
		
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)) {
			$questions[] = tx_wecassessment_question::newFromArray($row);
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($result);
		
		/* @todo 	Not a true randomize.  We're only shuffling questions on the current page. */
		if($randomize) {
			shuffle($questions);
		}
		
		return $questions;
	}
	
	/**
	 * Creates a new question object from an associative array.
	 *
	 * @param		array		Associate array for a question.
	 * @return		object		Answer object.
	 */
	function newFromArray($row) {
		$table = 'tx_wecassessment_question';
		$row = tx_wecassessment_question::processRow($table, $row);
		$questionClass = t3lib_div::makeInstanceClassName($table);
		return new $questionClass($row['uid'], $row['pid'], $row['sorting'], $row['text'], $row['category_id'], $row['weight']);
	}	
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/model/class.tx_wecassessment_question.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/model/class.tx_wecassessment_question.php']);
}

?>