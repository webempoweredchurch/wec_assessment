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

require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_responsecontainer.php');
require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_question.php');
require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_response_category.php');

class tx_wecassessment_category extends tx_wecasssessment_responsecontainer {
	
	var $_uid;
	var $_pid;
	var $_title;
	var $_description;
	var $_image;
	
	var $_responseClass = 'tx_wecassessment_response_category';
		
	/**
	 * Default constructor.
	 * @param		integer		Unique ID for the category.
	 * @param		integer		Page ID where the category is stored.
	 * @param		string		Title of the category.
	 * @param		string		Description of the category.
	 * @param		string		Path to image associated with the category.
	 * @param		integer		Unique ID for the parent category for this category.
	 * @return		none
	 */
	function tx_wecassessment_category($uid, $pid, $title, $description, $image) {
		$this->_uid = $uid;
		$this->_pid = $pid;
		$this->_title = $title;
		$this->_description = $description;
		$this->_image = $image;
		
		$this->_validationErrors = array();
	}
	
	/**
	 * Converts category object into an array.
	 *
	 * @return		array		Array representation of category object.
	 */
	function toArray() {
		return array(
			"uid" => $this->getUID(), 
			"pid" => $this->getPID(), 
			"title" => $this->getTitle(), 
			"description" => $this->getDescription(), 
			"image" => $this->getImage(), 
		);
	}
	
	function calculateResponse($score) {
		return tx_wecassessment_response_category::findByScore($score, $this->getUID());
	}

	
	/*************************************************************************
	 *
	 * Default Getters and Setters
	 *
	 ************************************************************************/
	
	/**
	 * Gets the UID of the category.
	 *
	 * @return		integer		The UID of the category.
	 */
	function getUID() { 
		return $this->_uid; 
	}
	
	/**
	 * Sets the UID of the category.
	 *
	 * @param		ingeter		The UID of the category.
	 * @return		none
	 */
	function setUID($uid) { 
		$this->_uid = $uid; 
	}

	/**
	 * Gets the PID that the category is stored on.
	 *
	 * @return		integer		The PID of the category.
	 */
	function getPID() { 
		return $this->_pid; 
	}
	
	/**
	 * Sets the PID that the category is stored on.
	 *
	 * @param		integer		The PID of the category.
	 * @return		none
	 */
	function setPID($pid) { 
		$this->_pid = $pid; 
	}
	
	/**
	 * Gets the title of the category.
	 *
	 * @return		string		The title of the category.
	 */
	function getTitle() { 
		return $this->_title; 
	}
	
	
	/**
	 * Sets the title of the category.
	 *
	 * @param		string		The title of the category.
	 * @return		none
	 */
	function setTitle($title) { 
		$this->_title = $title; 
	}
	
	/**
	 * Gets the description of the category.
	 *
	 * @return		string		The description of the category.
	 */
	function getDescription() { 
		return $this->_description; 
	}
	
	/**
	 * Sets the description of the category.
	 *
	 * @param		string		The description of the category.
	 * @return		none
	 */
	function setDescription($description) { 
		$this->_description = $description; 
	}
	

	/**
	 * Gets the path to the category image.
	 *
	 * @return		string		Path to the category image.
	 */
	function getImage() { 
		return $this->_image; 
	}
	
	/**
	 * Sets the path to the category image.
	 *
	 * @param		string		Path to the category image.
	 * @return		none
	 */
	function setImage($image) { 
		$this->_image = $image;
	}
	
	/**
	 * Gets the label for the current record.
	 *
	 * @return		string
	 */
	function getLabel() {
		return $this->getTitle();
	}
	
	
	
	/*************************************************************************
	 *
	 * Static Methods
	 *
	 ************************************************************************/
	
	/**
	 * Finds a specific category by its UID.
	 * @param		integer		The unique identifier of the category.
	 * @return		object		The category object.
	 */
	function find($uid, $showHidden=false) {
		$table = 'tx_wecassessment_category';
		if($uid) {
			$row = tx_wecassessment_category::getRow($table, $uid, '', $showHidden);
			$category = tx_wecassessment_category::newFromArray($row);
		}
		
		return $category;
	}
	
	/**
	 * Finds all categories on a given page.
	 * @param		integer		Page ID categories are stored on.
	 * @param		string		SQL WHERE clause for additional filtering.
	 * @return		array		Array of categories matched by PID and WHERE clause.
	 */
	function findAll($pid, $additionalWhere='') {
		$categories = array();
		$table = 'tx_wecassessment_category';
		
		$where = tx_wecassessment_category::combineWhere($additionalWhere, 'pid='.$pid);
		$where = tx_wecassessment_category::getWhere($table, $where);
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', $table, $where);		
		
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)) {
			$categories[] = tx_wecassessment_category::newFromArray($row);
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($result);
		
		return $categories;
	}
	
	/**
	 * Creates a new category object from an associative array.
	 *
	 * @param		array		Associate array for a category.
	 * @return		object		Answer object.
	 */
	function newFromArray($row) {
		$table = 'tx_wecassessment_category';
		
		$row = tx_wecassessment_category::processRow($table, $row);
		$categoryClass = t3lib_div::makeInstanceClassName($table);
		return new $categoryClass($row['uid'], $row['pid'], $row['title'], $row['description'], $row['image']);
	}

	function findQuestions($recur = false) {
		$where = 'category_id='.$this->getUID();
		return tx_wecassessment_question::findAll($this->getPID(), $where);
	}
	
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/model/class.tx_wecassessment_category.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/model/class.tx_wecassessment_category.php']);
}

?>