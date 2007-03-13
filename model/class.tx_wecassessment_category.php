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

class tx_wecassessment_category extends tx_wecassessment_modelbase {
	
	var $_uid;
	var $_pid;
	var $_title;
	var $_description;
	var $_image;
	var $_parent;
	var $_parentUID;
	var $_children;
	var $_responses;
	
	var $_validationErrors;
	
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
	function tx_wecassessment_category($uid, $pid, $title, $description, $image, $parentUID) {
		$this->_uid = $uid;
		$this->_pid = $pid;
		$this->_title = $title;
		$this->_description = $description;
		$this->_image = $image;
		$this->_parentUID = $parentUID;
		
		$this->_validationErrors = array();
	}
	
	/**
	 * Converts category object into an array.
	 *
	 * @return		array		Array representation of category object.
	 */
	function toArray() {
		return array("uid" => $this->getUID(), "pid" => $this->getPID(), "title" => $this->getTitle(), "description" => $this->getDescription(), "image" => $this->getImage(), "parent_id" => $this->getParentUID());
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
	 * Gets the UID of the parent category.
	 *
	 * @return		UID of the parent category.
	 */
	function getParentUID() { 
		return $this->_parentUID;
	}

	/**
	 * Sets the UID of the parent category.  Also, unsets $this->_parent which
	 * will be reloaded with the new UID when needed.
	 *
	 * @param		integer		UID of the parent category.
	 * @return		none
	 */
	function setParentUID($parentUID) { 
		$this->_parentUID = $parentUID; 
		unset($this->_parent);
	}
	
	/**
	 * Finds the parent of the current category.  If the category is top-level
	 * and no parent exists, return null.
	 *
	 * @return		object		The parent category.
	 */
	function getParentCategory() {
		if(!$this->_parent) {
			$this->_parent = tx_wecassessment_category::find($this->getParentUID());
		}
		return $this->_parent;
	}
	
	/**
	 * Sets the parent of the current category.  Also, resets $this->_parentUID
	 * to the new UID.
	 *
	 * @param		object		The parent category.
	 * @return		none.
	 */
	function setParentCategory($parent) {
		$this->_parent = $parent;
		$this->_parentUID = $parent->getUID();
	}
	
	
	/**
	 * Finds the children of the current category.  If there are no children,
	 * then an empty array is returned.
	 *
	 * @return		array		Array of categories that are children of the current category.
	 */
	function getChildCategories() {
		if(!$this->_children) {
			$this->_children = tx_wecassessment_category::findAll($this->getPID(), 'parent_category='.$this->getUID());
		}
		return $this->_children;
	}
	
	/* @todo		Do we need setChildCategories()? */
	
	/**
	 * Gets the responses within the current category.
	 * 
	 * @return		array		The array for responses belonging to the current category.
	 * @todo		Order responses based on min value.
	 */
	function getResponses() {
		if(!$this->_responses) {
			$this->_responses = tx_wecassessment_response::findAllInCategory($this->getPID(), $this->getUID());
		}
		return $this->_responses;
		
	}
	
	
	/**
	 * Sets the responses to the specified response array.
	 * @param		array		The array of responses in this category.
	 * @return		none
	 */	
	function setResponses($responses) {
		foreach($responses as $response) {
			$this->addResponse($response);
		}
	}
	
	/**
	 * Adds a single response to the array of responses in this category.
	 * @param		object		The new response object.
	 * @return		none
	 */
	function addResponse($response) {
		$response->setCategoryUID($this->getUID());
		$this->_responses[] = $response;
	}
	
	/**
	 * Checks if the current category is a child of the given categoryUID.
	 * Starts from the current category's parent and recurs up the hierarchy.
	 *
	 * @param		integer		The category UID that we're testing for inclusion within.
	 * @return		boolean		True/false whether the current category lives within categoryUID.
	 */
	function inCategory($categoryUID) {
		$parentCategory = $this->getParentCategory();
		
		/* If there is a parent, search. Else, not in category. */
		if($parentCategory) {

			/* If the parent matches what we're looking for, in category.
			 * If not, check the parent 
			 */
			if($parentCategory == $categoryUID) {
				$inCategory = true;
			} else {
					$inCategory = $parentCategory->inCategory($categoryUID);
			}	
		} else {
			$inCategory = false;
		}
		
		return $inCategory;
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
	function find($uid) {
		$table = 'tx_wecassessment_category';
		$row = tx_wecassessment_category::getRow($table, $uid);
		
		$category = tx_wecassessment_category::newFromArray($row);
		
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
		
		return $categories;
	}
	
	/**
	 * Finds root level categories.  That is, categories without parents.
	 * @param		integer		Page ID where categories are stored.
	 * @return		array		Array of root level categories.
	 */
	function findAllParents($pid) {
		return tx_wecassessment_category::findAll($pid, 'parent_category=0');
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
		return new $categoryClass($row['uid'], $row['pid'], $row['title'], $row['description'], $row['image'], $row['parent_category']);			
	}


	/*************************************************************************
	 *
	 * Validation functions
	 *
	 ************************************************************************/
	
	/* @todo		Review validation functions */
	
	/**
	 * Validates responses for this category based on the min and max parameters.
	 * Looks for overlapping ranges and uncovered ranges.
	 *
	 * @param		integer		The minimum value for a response.
	 * @param		integer		The maximum value for a response.
	 * @return		boolean		True/false whether category is valid or not.
	 */
	function valid($min, $max) {
		$categoryIsValid = true;
		$responses = $this->getResponses();
		$messages = array();
		
		foreach($responses as $response) {
			$hasErrors = false;
			
			if(!$response->valid($min, $max)) {
				$responseHasErrors = true;
			}
			
			if($previousResponse and !$response->validRelativeTo($previousResponse)) {
				$responseHasErrors = true;
			}
			
			if($responseHasErrors) {
				$this->addValidationErrors($response->getValidationErrors());
				$categoryIsValid = false;
			}
			
			$previousResponse = $response;
		}
		
		return $categoryIsValid;

	}
	
	/**
	 * Adds validation errors the existing error array.
	 *
	 * @param		array		Array of new validation errors.
	 * @return		none
	 */
	function addValidationErrors($errorArray) {
		$this->_validationErrors = array_merge($this->_validationErrors, $errorArray);
	}
	
	/**
	 * Gets the validation errors for the current category.
	 *
	 * @return		array		Array of validation errors.
	 */
	function getValidationErrors() {
		return $this->_validationErrors;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/* @todo	These probably aren't needed any more */
	
	/**
	 * Check for min/max pairings where there are holes between values.
	 * Assummes that responses are already sorted by minValue and that
	 * checkRangePairings has not found errors.
	 */
	function checkResponseHoles($min, $max) {
		$errors = array();
		$responses = $this->getResponses();
		
				
		/* Check min to first response range */
		$firstResponse = $responses[0];
		if($min < $firstResponse->getMinValue()) {
			$errors[] = "Response range gap between ".$min." and ".$firstResponse->getMinValue();
		}
		
		for($i=0; $i<count($responses)-1; $i++) {
			//tx_wecassessment_response::error($response, "No response exists for ".$lowerBound." to ".$upperBound);
			
		}
		
		/* Check last response range to max */
		$lastResponse = $responses[count($responses)-1];
		if($max > $lastResponse->getMaxValue()) {
			$errors[] = "Response range gap between ".$lastResponse->getMaxValue()." and ".$max;
		}
				
		return $errors;
	}
	
	function checkResponseOverlap() {
		$errors = array();
		$responses = $this->getResponses();
		
		for($i=0; $i<count($responses)-2; $i++) {
			$currentResponse = $responses[$i];
			$nextResponse = $responses[$i+1];

			if($currentResponse->getMaxValue() > $nextResponse->getMinValue) {
				$errors[] = "Response ranges overlap";
			}
		}
		
		return $errors;
	}
	
}

?>