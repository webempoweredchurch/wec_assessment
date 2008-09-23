<?php
/***************************************************************
* Copyright notice
*
* (c) 2007-2008 Christian Technology Ministries International Inc.
* All rights reserved
*
* This file is part of the Web-Empowered Church (WEC)
* (http://WebEmpoweredChurch.org) ministry of Christian Technology Ministries 
* International (http://CTMIinc.org). The WEC is developing TYPO3-based
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

require_once(t3lib_extMgm::extPath('wec_assessment') . 'model/class.tx_wecassessment_modelbase.php');

class tx_wecassessment_recommendationcontainer extends tx_wecassessment_modelbase {
	
	var $_validationErrors;
	var $_recommendations;
	
	/**
	 * Sets the recommendations to the specified recommendation array.
	 * @param		array		The array of recommendations in this category.
	 * @return		none
	 */	
	function setRecommendations($recommendations) {
		// @todo 	Unset recommendations first
		foreach((array) $recommendations as $recommendation) {
			$this->addRecommendation($recommendation);
		}
	}
	
	/**
	 * Gets the recommendations within the current category.
	 * 
	 * @return		array		The array for recommendations belonging to the current category.
	 * @todo		Order recommendations based on min value.
	 */
	function getRecommendations() {
		if(!$this->_recommendations) {
			$recommendationClass = $this->_recommendationClass;
			switch($recommendationClass) {
				case 'tx_wecassessment_recommendation_assessment':
					$this->_recommendations = tx_wecassessment_recommendation_assessment::findAllInParent($this->getPID(), $this->getUID());
					break;
				case 'tx_wecassessment_recommendation_category':
					$this->_recommendations = tx_wecassessment_recommendation_category::findAllInParent($this->getPID(), $this->getUID());
					break;
				case 'tx_wecassessment_recommendation_question':
					$this->_recommendations = tx_wecassessment_recommendation_question::findAllInParent($this->getPID(), $this->getUID());
					break;					
			}
		}
		return $this->_recommendations;
		
	}
	
	/**
	 * Adds a single recommendation to the array of recommendations in this category.
	 * @param		object		The new recommendation object.
	 * @return		none
	 */
	function addRecommendation($recommendation) {
		$recommendation->setParentUID($this->getUID());
		$this->_recommendations[] = $recommendation;
	}
	
	
	
	/**
	 * Adds validation errors the existing error array.
	 *
	 * @param		array		Array of new validation errors.
	 * @return		none
	 */
	function addValidationErrors($errorArray) {
		if(is_array($this->_validationErrors)) {
			$this->_validationErrors = array_merge($this->_validationErrors, $errorArray);
		} else {
			$this->_validationErrors = $errorArray;
		}
	}
	
	/**
	 * Gets the validation errors for the current category.
	 *
	 * @return		array		Array of validation errors.
	 */
	function getValidationErrors() {
		return $this->_validationErrors;
	}
	
	/**
	 * Validates recommendations for this category based on the min and max parameters.
	 * Looks for overlapping ranges and uncovered ranges.
	 *
	 * @param		integer		The minimum value for a recommendation.
	 * @param		integer		The maximum value for a recommendation.
	 * @return		boolean		True/false whether category is valid or not.
	 */
	function valid($min, $max) {
		$containerIsValid = true;
		$messages = array();
		$recommendations = $this->getRecommendations();
				
		if(is_array($recommendations) and count($recommendations) > 0) {
			$previousRecommendation = null;
			$lastUID = $recommendations[count($recommendations)-1]->getUID();
						
			foreach((array) $recommendations as $recommendation) {
				$hasErrors = false;
				
				// If we're on the first recommendation, check the lower bound
				if(is_null($previousRecommendation)) {
					if(!$recommendation->againstLowerBound($min)) {
						$recommendationHasErrors = true;
					}
				}
				
				// If we're on the last recommendation, check the upper bound
				if($recommendation->getUID() == $lastUID) {
					if(!$recommendation->againstUpperBound($max)) {
						$recommendationHasErrors = true;
					}
				}
			
				if(!$recommendation->valid($min, $max)) {
					$recommendationHasErrors = true;
				}
			
				if($previousRecommendation and !$recommendation->validRelativeTo($previousRecommendation)) {
					$recommendationHasErrors = true;
				}
			
				if($recommendationHasErrors) {
					$this->addValidationErrors($recommendation->getValidationErrors());
					$containerIsValid = false;
				}
			
				$previousRecommendation = $recommendation;
			}
		}
		
		return $containerIsValid;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/model/class.tx_wecassessment_recommendationcontainer.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/model/class.tx_wecassessment_recommendationcontainer.php']);
}
?>
