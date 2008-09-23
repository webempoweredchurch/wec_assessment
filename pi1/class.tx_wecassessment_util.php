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

class tx_wecassessment_util {
	
	/*
	 * Default constructor.
	 * @param		object		The parent object to the util class.
	 * @return		none
	 */
	function tx_wecassessment_util(&$pObj) {
		$this->cObj = $pObj->cObj;
		$this->template = $pObj->template;
	}
	
	
	/*
	 * Returns a the FlexForm value for the specified sheet and field.
	 * @param		string		The name of the FlexForm sheet.
	 * @param		string		The name of the FlexForm field.
	 * @return		mixed		The value of the field.
	 */
	function getFlexFormValue($sheet, $field) {
		$piFlexForm = $this->cObj->data['pi_flexform'];
		$value = $piFlexForm['data'][$sheet]['lDEF'][$field]['vDEF'];
		
		return $value;
	}
	
	/*
	 * Evaluates specified Typoscript key with the given data array.
	 * @param		array		The data array to be used in the cObject.
	 * @param		string		The Typoscript key containing the cObject.
	 * @return		string		The output from the cObject/data evaluation, typically HTML.
	 * @todo		Clean up this interface!  The duplicated parent/key setup is not nice.
	 */
	function getOutputFromCObj($dataArray, $parent, $key) {
		$local_cObj = t3lib_div::makeInstance('tslib_cObj');
		$local_cObj->start($dataArray);
		$content[] = $local_cObj->cObjGetSingle($parent[$key], $parent[$key . '.']);

		return implode(chr(10), $content);
		
	}
	
	function wrap(&$content, $wrap) {
		$local_cObj = t3lib_div::makeInstance('tslib_cObj');
		$local_cObj->start(array());
		$content = $local_cObj->stdWrap($content, $wrap);
	}
	
	
	/*
	 * Loads a template file and extracts a subpart tied to the current cmd.
	 * @param		string		The command (ex.  displayQuestions).
	 * @param		string		The path to the template file.
	 * @return		none
	 */
	function loadTemplate($cmd, $templateFile) {
		//	Get the file location and name of our template file
		$template = $this->cObj->fileResource( $templateFile );
		switch($cmd) {
			case 'displayQuestions':
				$subpart = 'display_questions';
				break;
			case 'displayRecommendations':
			 	$subpart = 'display_recommendations';
				break;
			default:
				$subpart = $cmd;
		}
		
		$templateSubpart = $this->getTemplateSubpart($subpart, $template);
		$this->template = $templateSubpart;
	}
	
	function getTemplate() {
		return $this->template;
	}
	
	function getTemplateSubpart($subpart, $template='') {
		if(!$template) {
			$template = $this->template;
		}
		
		return $this->cObj->getSubpart($template, '###' . strtoupper($subpart) . '###');
	}
	
	/*
	 * Subsitute markers with actual content.
	 * @param		array		Associative array with marker/content pairings.
	 * @return		none
	 * @todo		Does this actually work?
	 */
	function substituteMarkers(&$template, $markers) {
		$cObj = t3lib_div::makeInstance('tslib_cObj');
		$template = $cObj->substituteMarkerArray($template, $markers, $wrap='###|###',$uppercase=1);
	}
	
	function substituteSubparts(&$template, $subparts) {
		foreach((array) $subparts as $label => $content) {
			$cObj = t3lib_div::makeInstance('tslib_cObj');
			$template = $cObj->substituteSubpart($template, '###' .strtoupper($label) . '###', $content);
		}
	}
	
	
	function substituteMarkersAndSubparts(&$template, $markers, $subparts) {
		$this->substituteMarkers($template, $markers);
		$this->substituteSubparts($template, $subparts);
	}
	
	
	function getMarkers($template='') {
		preg_match_all('!\<\!--[a-zA-Z0-9 ]*###([A-Z0-9_-|]*)\###[a-zA-Z0-9 ]*-->!is', $this->template, $match);
		$subparts = array_unique($match[1]);
		
		foreach ($subparts as $subpart) {
		}
		
		preg_match_all('!\###([A-Z0-9_-|]*)\###!is', $this->template, $match);
		$markers = array_unique($match[1]);
		$markers = array_diff($markers, $subparts);
		
		foreach ($markers as $marker) {
		}
			
	}
	
	function devLog($message) {
		if(TYPO3_DLOG) {
			t3lib_div::devLog($message, 'wec_assessment');
		}
	}
	
	
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/pi1/class.tx_wecassessment_util.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/pi1/class.tx_wecassessment_util.php']);
}
?>
