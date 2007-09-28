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

require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_response.php');

class tx_wecassessment_response_category extends tx_wecassessment_response {
	var $_categoryUID;
	var $_category;
	var $_tableName = 'tx_wecassessment_response';
	
	
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
	function tx_wecassessment_response_category($uid, $pid, $text, $minValue, $maxValue, $categoryUID) {
		$this->_uid = $uid;
		$this->_pid = $pid;
		$this->_text = $text;
		$this->_min_value = $minValue;
		$this->_max_value = $maxValue;
		$this->_categoryUID = $categoryUID;
		$this->_type = TX_WECASSESSMENT_RESPONSE_CATEGORY;
		
		$this->_validationErrors = array();
	}
	
	/**
	 * Converts a response to an associative array
	 *
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
			"score" => $this->getScore(),
			"maxScore" => $this->getMaxScore(),
			"parentTitle" => $category->getTitle(),
			"parentText" => "",
		);
		
		return $responseArray;
	}
	
	
	function getLabel() {
		$category = &$this->getCategory();
		if(is_object($category)) {
			$title = $category->getTitle();
		} else {
			$title = '[ No Category ]';
		}

		return $title.': '.$this->getMinValue().'-'.$this->getMaxValue();
	}
	

	
	/*************************************************************************
	 *
	 * Default Getters and Setters
	 *
	 ************************************************************************/
	
	/**
	 * Gets the UID of the category this response belongs to.
	 *
	 * @return		integer		UID of the category.
	 */
	function getCategoryUID() { 
		return $this->_categoryUID; 
	}
	
	function getParentUID() {
		return $this->getCategoryUID();
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
	
	function setParentUID($uid) {
		$this->setCategoryUID($uid);
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
	function findByScore($score, $categoryID) {
		$table = 'tx_wecassessment_response';
		$where = tx_wecassessment_response_category::getWhere($table, 'min_value <= '.$score.' AND max_value > '.$score.' AND category_id='.$categoryID.' AND type='.TX_WECASSESSMENT_RESPONSE_CATEGORY);
		
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', $table, $where);
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result);
		$GLOBALS['TYPO3_DB']->sql_free_result($result);
		
		if(is_array($row)) {
			$response = &tx_wecassessment_response_category::newFromArray($row);
			$response->setScore($score);
		} else {
			$response = null;
		}
		
		return $response;
	}
	
	
	function findAll($pid, $additionalWhere="") {
		$responses = tx_wecassessment_response_category::findAllWithType($pid, $additionalWhere, TX_WECASSESSMENT_RESPONSE_CATEGORY);
		return $responses;
	}
	
	function findAllInCategory($pid, $category_id, $additionalWhere="") {
		$where = tx_wecassessment_response_category::combineWhere($additionalWhere, 'pid='.$pid.' AND category_id='.$category_id);
		$responses = tx_wecassessment_response_category::findAll($pid, $where);
		
		return $responses;
	}
	
	function findAllInParent($pid, $parentUID, $additionalWhere="") {
		return tx_wecassessment_response_category::findAllInCategory($pid, $parentUID, $additionalWhere);
	} 
	
	function findResponsesAndErrors($pid) {
		$responses = tx_wecassessment_response_category::findAll($pid);
		$errors = tx_wecassessment_response_category::findErrors($pid);
	}
	
	/**
	 * Creates a new response object from an associative array.
	 *
	 * @param		array		Associate array for a response.
	 * @return		object		Answer object.
	 */
	function newFromArray($row) {
		$table = 'tx_wecassessment_response';
		$row = tx_wecassessment_response_category::processRow($table, $row);
		$responseClass = 'tx_wecassessment_response_category';
		return new $responseClass($row['uid'], $row['pid'], $row['text'], $row['min_value'], $row['max_value'], $row['category_id']);
	}
	
	/* @todo 		Where should this live? */
	function calculate($score) {
		/* @todo 	ugly hack!  If we have a perfect score, back it down a tiny bit so that we get a response */
		if($value == $maxValue) {
			$response = tx_wecassessment_response::findByValue($value-0.01, $category->getUID());
		} else {
			$response = tx_wecassessment_response::findByValue($value, $category->getUID());
		}
		
		if(is_object($response)) {				
			$response->setScore($value);
			$response->setMaxScore($highTotal / $weightTotal);
		}
		
		return $response;
	}
	

		
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/model/class.tx_wecassessment_response_category.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/model/class.tx_wecassessment_response_category.php']);
}
?>