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
	
	var $_minimumValue = 1;
	var $_maximumValue = 6;
	var $_answerSet = array(
		'1' => 'Never ever ever.',
		'2' => 'Once in a blue moon.',
		'3' => 'Every now and again',
		'4' => 'Sometimes',
		'5' => 'Every single day',
		'6' => 'Every waking moment',
	);

	function getAnswerSet() {
		return $this->_answerSet;
	}
	
	function getMinimumValue() {
		return $this->_minimumValue;
	}
	
	function getMaximumValue() {
		return $this->maximumValue;
	}
	
}