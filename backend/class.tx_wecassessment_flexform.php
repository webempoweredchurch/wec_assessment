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

require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_recommendation.php');
require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_category.php');
require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_assessment.php');

class tx_wecassessment_flexform {
	
	
	/**
	 * Common initialization function.
	 * @param		array		The array of all FlexForm elements.
	 * @param		array		Form object.
	 * @return		none
	 */
	function init($PA, $fobj) {
		$this->PA = $PA;
		$flexform = $this->PA['row']['pi_flexform'];
		$assessmentClass = t3lib_div::makeInstanceClassName('tx_wecassessment_assessment');
		$this->assessment = new $assessmentClass($PA['row']['uid'], $PA['row']['pid'], '', t3lib_div::xml2array($flexform));
	}

	/**
	 * Gets the value of partcular FlexForm key on a particular sheet.
	 * @param		string		The name of the sheet.
	 * @param		string		The name of the FlexForm field.
	 * @return		mixed		The value of the FlexForm field.
	 */
	function getFlexFormValue($sheet, $field) {
		$flexform = $this->PA['row']['pi_flexform'];
		$ffArray = t3lib_div::xml2array($flexform);

		if(is_array($ffArray)) {
			$value = $ffArray['data'][$sheet]['lDEF'][$key]['vDEF'];
		}
		 
		return $value;
	}
	

	
	/**
	 * Gets the HTML for the scale form labels.
	 *
	 * @param		array		The array of all FlexForm elements.
	 * @param		array		fobj
	 * @return		string		The HTML for the scale form.
	 */
	function getScaleForm($PA, $fobj) {
		$this->init($PA, $fobj);
		$labels = $this->assessment->getAnswerSet();
		
		/* For each possible value, build the form elements. */
		for($i=$this->assessment->getMinimumValue(); $i<=$this->assessment->getMaximumValue(); $i++) {
			$output[] = '<div style="margin-bottom: 3px"><label for="tx_wecassessment_label_'.$i.'" style="vertical-align: baseline;">'.$i.'</label>
								<input name="data[tt_content]['.$this->assessment->getUID().'][pi_flexform][data][labels][lDEF][scale_label][vDEF]['.$i.']" id="tx_wec_assessment_label_'.$i.'" value="'.$labels[$i].'"/></div>';
		}			
		return implode(chr(10), $output);
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/backend/class.tx_wecassessment_flexform.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/backend/class.tx_wecassessment_flexform.php']);
}


?>