<?php
require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_modelbase.php');

class tx_wecasssessment_responsecontainer extends tx_wecassessment_modelbase {
	
	var $_validationErrors;
	var $_responses;
	
	/**
	 * Sets the responses to the specified response array.
	 * @param		array		The array of responses in this category.
	 * @return		none
	 */	
	function setResponses($responses) {
		/* @todo 	Unset responses first */
		foreach($responses as $response) {
			$this->addResponse($response);
		}
	}
	
	/**
	 * Gets the responses within the current category.
	 * 
	 * @return		array		The array for responses belonging to the current category.
	 * @todo		Order responses based on min value.
	 */
	function getResponses() {
		if(!$this->_responses) {
			$responseClass = $this->_responseClass;
			switch($responseClass) {
				case 'tx_wecassessment_response_assessment':
					$this->_responses = tx_wecassessment_response_assessment::findAllInParent($this->getPID(), $this->getUID());
					break;
				case 'tx_wecassessment_response_category':
					$this->_responses = tx_wecassessment_response_category::findAllInParent($this->getPID(), $this->getUID());
					break;
				case 'tx_wecassessment_response_question':
					$this->_responses = tx_wecassessment_response_question::findAllInParent($this->getPID(), $this->getUID());
					break;					
			}
		}
		return $this->_responses;
		
	}
	
	/**
	 * Adds a single response to the array of responses in this category.
	 * @param		object		The new response object.
	 * @return		none
	 */
	function addResponse($response) {
		$response->setParentUID($this->getUID());
		$this->_responses[] = $response;
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
	 * Validates responses for this category based on the min and max parameters.
	 * Looks for overlapping ranges and uncovered ranges.
	 *
	 * @param		integer		The minimum value for a response.
	 * @param		integer		The maximum value for a response.
	 * @return		boolean		True/false whether category is valid or not.
	 */
	function valid($min, $max) {
		$containerIsValid = true;
		$messages = array();
		$responses = $this->getResponses();
				
		if(is_array($responses) and count($responses) > 0) {
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
					$containerIsValid = false;
				}
			
				$previousResponse = $response;
			}
		}
		
		return $containerIsValid;

	}
	
	
}


?>