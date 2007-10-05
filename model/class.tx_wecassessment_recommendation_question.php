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

require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_recommendation.php');

class tx_wecassessment_recommendation_question extends tx_wecassessment_recommendation {
	var $_questionUID;
	var $_question;
	var $_tableName = 'tx_wecassessment_recommendation';
	
	
	/**
	 * Default constructor.
	 *
	 * @param		integer		UID of the recommendation.
	 * @param		integer		Page ID of the recommendation.
	 * @param		string		Text of the recommendation.
	 * @param		integer		Min value this recommendation is displayed for.
	 * @param		integer		Max value this recommendation is displayed for.
	 * @param		integer		Question UID this recommendation belongs to.
	 */
	function tx_wecassessment_recommendation_question($uid, $pid, $text, $minValue, $maxValue, $questionUID) {
		$this->_uid = $uid;
		$this->_pid = $pid;
		$this->_text = $text;
		$this->_min_value = $minValue;
		$this->_max_value = $maxValue;
		$this->_questionUID = $questionUID;
		$this->_type = TX_WECASSESSMENT_RESPONSE_QUESTION;
		
		$this->_validationErrors = array();
	}
	
	/**
	 * Converts a recommendation to an associative array
	 *
	 * @return		Associative array representing the recommendation.
	 */
	function toArray() {
		$question = $this->getQuestion();
		
		return array(
			"uid" => $this->getUID(),
			"pid" => $this->getPID(),
			"text" => $this->getText(),
			"min_value" => $this->getMinValue(),
			"max_value" => $this->getMaxValue(),
			"score" => $this->getScore(),
			"maxScore" => $this->getMaxScore(),
			"parentTitle" => "",
			"parentText" => $question->getText(),
		);
		
		return $recommendationArray;
	}
	
	function getLabel() {
		$question = &$this->getQuestion();
		if(is_object($question)) {
			$title = $question->getLabel();
		} else {
			$title = '[ No Question ]';
		}

		return $this->getMinValue().'-'.$this->getMaxValue().' : '.$title;
	}
	

	
	/*************************************************************************
	 *
	 * Default Getters and Setters
	 *
	 ************************************************************************/
	
	/**
	 * Gets the UID of the question this recommendation belongs to.
	 *
	 * @return		integer		UID of the question.
	 */
	function getQuestionUID() { 
		return $this->_questionUID; 
	}
	
	function getParentUID() {
		return $this->getQuestionUID();
	}
	
	/**
	 * Sets the UID of the question this recommendation belongs to. Also, unsets
	 * $this->_question and waits for it be reloaded as needed.
	 *
	 * @param		integer		UID of the question.
	 * @return		none
	 */
	function setQuestionUID($uid) { 
		$this->_questionUID = $uid; 
		unset($this->_question); 
	}
	
	function setParentUID($uid) {
		$this->setQuestionUID($uid);
	}
	
	/**
	 * Gets the question this recommendation belongs to.
	 *
	 * @return		object		The question.
	 */
	function getQuestion() {
		if(!$this->_question and $this->getQuestionUID()) {
			$this->_question = tx_wecassessment_question::find($this->getQuestionUID());
		}
		
		return $this->_question;
	}
	
	/**
	 * Sets the question this recommendation belongs to.  Also, resets $this->_questionUID
	 * to the updated UID.
	 *
	 * @param		object		The question.
	 * @return		none
	 */
	function setQuestion($question) { 
		$this->_question = $question; 
		$this->_questionUID = $question->getUID(); 
	}

	
	/*************************************************************************
	 *
	 * Static Functions
	 *
	 ************************************************************************/
	function findByScore($score, $questionID) {
		$table = 'tx_wecassessment_recommendation';
		$where = tx_wecassessment_recommendation_question::getWhere($table, 'min_value <= '.$score.' AND max_value > '.$score.' AND question_id='.$questionID.' AND type='.TX_WECASSESSMENT_RESPONSE_QUESTION);
		
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', $table, $where);
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result);
		$GLOBALS['TYPO3_DB']->sql_free_result($result);
		
		if(is_array($row)) {
			$recommendation = &tx_wecassessment_recommendation_question::newFromArray($row);
			$recommendation->setScore($score);
		} else {
			$recommendation = null;
		}
		
		return $recommendation;
	}
	
	
	function findAll($pid, $additionalWhere="") {
		$recommendations = tx_wecassessment_recommendation_question::findAllWithType($pid, $additionalWhere, TX_WECASSESSMENT_RESPONSE_QUESTION);
		return $recommendations;
	}
	
	function findAllInQuestion($pid, $question_id, $additionalWhere="") {
		$where = tx_wecassessment_recommendation_question::combineWhere($additionalWhere, 'pid='.$pid.' AND question_id='.$question_id);
		$recommendations = tx_wecassessment_recommendation_question::findAll($pid, $where);
		
		return $recommendations;
	}
	
	function findAllInParent($pid, $parent_id, $additionalWhere="") {
		return tx_wecassessment_recommendation_question::findAllInQuestion($pid, $parent_id, $additionalWhere);
	} 
	
	function findRecommendationsAndErrors($pid) {
		$recommendations = tx_wecassessment_recommendation_question::findAll($pid);
		$errors = tx_wecassessment_recommendation_question::findErrors($pid);
	}
	
	/**
	 * Creates a new recommendation object from an associative array.
	 *
	 * @param		array		Associate array for a recommendation.
	 * @return		object		Answer object.
	 */
	function newFromArray($row) {
		$table = 'tx_wecassessment_recommendation';
		$row = tx_wecassessment_recommendation_question::processRow($table, $row);
		$recommendationClass = 'tx_wecassessment_recommendation_question';
		return new $recommendationClass($row['uid'], $row['pid'], $row['text'], $row['min_value'], $row['max_value'], $row['question_id']);
	}
	
	
	
	
	
	/* @todo 		Where should this live? */
	function calculate($question, $answers, $minValue, $maxValue) {
		foreach($answers as $answer) {
			$question = $answer->getQuestion();
			
			$answerTotal += $answer->getWeightedScore();
			$weightTotal += $question->getWeight();
			
			$lowTotal += $question->getWeight() * $minValue;
			$highTotal += $question->getWeight() * $maxValue;
		}
				
		/* @todo		How to deal with weights of 0? */
		if ($weightTotal==0) {
			$weightTotal = 1;
		}
		
		$value = $answerTotal / $weightTotal;
		
		/* @todo 	ugly hack!  If we have a perfect score, back it down a tiny bit so that we get a recommendation */
		if($value == $maxValue) {
			$recommendation = tx_wecassessment_recommendation::findByValue($value-0.01, $question->getUID());
		} else {
			$recommendation = tx_wecassessment_recommendation::findByValue($value, $question->getUID());
		}
		
		if(is_object($recommendation)) {				
			$recommendation->setScore($value);
			$recommendation->setMaxScore($highTotal / $weightTotal);
		}
		
		return $recommendation;
	}
	

		
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/model/class.tx_wecassessment_recommendation_question.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/model/class.tx_wecassessment_recommendation_question.php']);
}
?>