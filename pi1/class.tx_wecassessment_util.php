<?php

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
		$content[] = $local_cObj->cObjGetSingle($parent[$key], $parent[$key.'.']);

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
			case 'displayResponses':
			 	$subpart = 'display_responses';
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
		
		return $this->cObj->getSubpart($template, "###".strtoupper($subpart)."###");
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
		foreach($subparts as $label => $content) {
			$cObj = t3lib_div::makeInstance('tslib_cObj');
			$template = $cObj->substituteSubpart($template, '###'.strtoupper($label).'###', $content);
		}
	}
	
	
	function substituteMarkersAndSubparts(&$template, $markers, $subparts) {
		$this->substituteMarkers($template, $markers);
		$this->substituteSubparts($template, $subparts);
	}
	
	
	function getMarkers($template='') {
		preg_match_all('!\<\!--[a-zA-Z0-9 ]*###([A-Z0-9_-|]*)\###[a-zA-Z0-9 ]*-->!is', $this->template, $match);
		$subparts = array_unique($match[1]);
		debug($subparts, "subparts");
		
		foreach ($subparts as $subpart) {
		}
		
		preg_match_all('!\###([A-Z0-9_-|]*)\###!is', $this->template, $match);
		$markers = array_unique($match[1]);
		$markers = array_diff($markers, $subparts);
		debug($markers, "markers");
		
		foreach ($markers as $marker) {
		}
			
	}
	
	
}

?>