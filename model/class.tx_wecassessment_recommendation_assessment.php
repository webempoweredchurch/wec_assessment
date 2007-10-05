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

class tx_wecassessment_recommendation_assessment extends tx_wecassessment_recommendation {
	
	var $_tableName = 'tx_wecassessment_recommendation';
	
	/**
	 * Default constructor.
	 *
	 * @param		integer		UID of the recommendation.
	 * @param		integer		Page ID of the recommendation.
	 * @param		string		Text of the recommendation.
	 * @param		integer		Min value this recommendation is displayed for.
	 * @param		integer		Max value this recommendation is displayed for.
	 */
	function tx_wecassessment_recommendation_assessment($uid, $pid, $text, $minValue, $maxValue) {
		$this->_uid = $uid;
		$this->_pid = $pid;
		$this->_text = $text;
		$this->_min_value = $minValue;
		$this->_max_value = $maxValue;
		$this->_type = TX_WECASSESSMENT_RESPONSE_ASSESSMENT;
		
		$this->_validationErrors = array();
	}
	
	function getLabel() {
		$title = 'Total Assessment';
		return $this->getMinValue().'-'.$this->getMaxValue().' : '.$title;
	}
	
	function getParentUID() {
		return 0;
	}
	
	function setParentUID($uid) {
		/* Do nothing */
	}
	
	/*************************************************************************
	 *
	 * Static Functions
	 *
	 ************************************************************************/
	function findByScore($score) {
		$table = 'tx_wecassessment_recommendation';
		$where = tx_wecassessment_recommendation_assessment::getWhere($table, ' AND min_value <= '.$value.' AND max_value > '.$value.' AND type='.TX_WECASSESSMENT_RESPONSE_ASSESSMENT);
		
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', $table, $where);
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result);
		$GLOBALS['TYPO3_DB']->sql_free_result($result);
		
		if(is_array($row)) {
			$recommendation = &tx_wecassessment_recommendation_assessment::newFromArray($row);
		} else {
			$recommendation = null;
		}
		
		return $recommendation;
	}
	
	
	function findAll($pid, $additionalWhere="") {
		$recommendations = tx_wecassessment_recommendation_assessment::findAllWithType($pid, $additionalWhere, TX_WECASSESSMENT_RESPONSE_ASSESSMENT);
		return $recommendations;
	}
	
	function findAllInAssessment($pid, $additionalWhere="") {
		return tx_wecassessment_recommendation_assessment::findAll($pid, $additionalWhere);
	}
	
	function findAllInParent($pid, $parentID=0, $additionalWhere="") {
		return tx_wecassessment_recommendation_assessment::findAll($pid, $additionalWhere);
	}
	
	function findRecommendationsAndErrors($pid) {
		$recommendations = tx_wecassessment_recommendation_assessment::findAll($pid);
		$errors = tx_wecassessment_recommendation_assessment::findErrors($pid);
	}
	
	/**
	 * Creates a new recommendation object from an associative array.
	 *
	 * @param		array		Associate array for a recommendation.
	 * @return		object		Answer object.
	 */
	function newFromArray($row) {
		$table = 'tx_wecassessment_recommendation';
		$row = tx_wecassessment_recommendation_asssessment::processRow($table, $row);
		$recommendationClass = t3lib_div::makeInstanceClassName($table);
		return new $recommendationClass($row['uid'], $row['pid'], $row['text'], $row['min_value'], $row['max_value']);
	}
	
	
	
	
	
	/* @todo 		Where should this live? */
	function calculate($answers, $minValue, $maxValue) {
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
			$recommendation = tx_wecassessment_recommendation_assessment::findByValue($value-0.01);
		} else {
			$recommendation = tx_wecassessment_recommendation_assessment::findByValue($value);
		}
		
		if(is_object($recommendation)) {				
			$recommendation->setScore($value);
			$recommendation->setMaxScore($highTotal / $weightTotal);
		}
		
		return $recommendation;
	}
	

		
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/model/class.tx_wecassessment_recommendation_assessment.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/model/class.tx_wecassessment_recommendation_assessment.php']);
}
?>