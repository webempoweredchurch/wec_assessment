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


class tx_wecassessment_assessment extends tx_wecassessment_modelbase {
	
	var $_minimumValue = 0;
	var $_maximumValue = 3;
	var $_answerSet = array(
		'0' => 'Never',
		'1' => 'Rarely',
		'2' => 'Sometimes.',
		'3' => 'Always',
	);
	var $_usePaging = false;
	var $_questionsPerPage = 0;
	
	var $_result;
	var $_pid = 0;
	var $_uid = 0;
	
	function tx_wecassessment_assessment($conf='') {
		if(is_array($conf)) {
			$this->_usePaging = $conf['usePaging'];
			$this->_questionsPerPage = $conf['questionsPerPage'];
			//$this->setMinimumValue($conf['minimumValue']);
			//$this->setMaximumValue($conf['maximumValue']);
		}
		
		if(is_array($flexform)) {
			
		}
		
	}
	
	function getUID() {
		return $this->_uid;
	}
	
	function setUID($value) {
		$this->_uid = $value;
	}
	
	function getPID() {
		return $this->_pid;
	}

	function setPID($value) {
		$this->_pid = $value;
	}

	function getAnswerSet() {
		return $this->_answerSet;
	}
	
	function getMinimumValue() {
		return $this->_minimumValue;
	}
	
	function setMinimumValue($value) {
		$this->_minimumValue = $value;
	}
	
	function getMaximumValue() {
		return $this->_maximumValue;
	}
	
	function setMaximumValue($value) {
		$this->_maximumValue = $value;
	}
	
	function usePaging() {
		return $this->_usePaging;
	}
	
	function setPaging($value) {
		$this->_usePaging = $value;
	}
	
	function getQuestionsPerPage() {
		return $this->_questionsPerPage;
	}
	
	function setQuestionsPerPage($value) {
		$this->_questionsPerPage = $value;
	}	
	
	function getResult() {
		if(!$this->_result) {			
			/* Get the result, either from the DB or the session */
			$this->_result = tx_wecassessment_result::findCurrent($this->_pid);
		}
		
		return $this->_result;
	}
	
}