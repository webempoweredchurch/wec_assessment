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
	 * @todo		Merge this with the backend function that has an exception for scale_labels.
	 */
	function getFlexFormValue($sheet, $field) {
		$piFlexForm = $this->cObj->data['pi_flexform'];
		$element = $piFlexForm['data'][$sheet]['lDEF'][$field];
		
		if(array_key_exists('vDEF', $element)) {
			$value = $element['vDEF'];
		} else {
			$value = $element;
		}
		
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
	
	function wrap($content, $wrap) {
		$local_cObj = t3lib_div::makeInstance('tslib_cObj');
		$local_cObj->start(array());
		$content = $local_cObj->stdWrap($content, $wrap);
		
		return $content;
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
				$subpart = 'questions';
				break;
			case 'displayResponses':
			 	$subpart = 'responses';
				break;
			default:
				$subpart = $cmd;
		}
		
		$templateSubpart = $this->cObj->getSubpart($template, "###".strtoupper($subpart)."###");
		$this->template = $templateSubpart;
	}
	
	/*
	 * Subsitute markers with actual content.
	 * @param		array		Associative array with marker/content pairings.
	 * @return		none
	 * @todo		Does this actually work?
	 */
	function substituteMarkers($markers) {
		$cObj = t3lib_div::makeInstance('tslib_cObj');
		$content = $cObj->substituteMarkerArray($this->template, $markers, $wrap='###|###',$uppercase=1);		
		return $content;
	}
	
	/* @todo Unused??? */
	function cObjGet($setup,$addKey='')	{
		if (is_array($setup))	{			
			$sKeyArray=t3lib_TStemplate::sortedKeyList($setup);
			$content ='';
			foreach($sKeyArray as $theKey)	{
				$theValue=$setup[$theKey];
				if (intval($theKey) && !strstr($theKey,'.'))	{
					$conf=$setup[$theKey.'.'];
					$content.=$this->cObjGetSingle($theValue,$conf,$addKey.$theKey);	// Get the contentObject
				}
			}
			return $content;
		}
	}
	
}

?>