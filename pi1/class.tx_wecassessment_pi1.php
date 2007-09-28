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

require_once(PATH_tslib.'class.tslib_pibase.php');
require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_question.php');
require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_response.php');
require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_answer.php');
require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_result.php');
require_once(t3lib_extMgm::extPath('wec_assessment').'pi1/class.tx_wecassessment_util.php');
require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_assessment.php');



/**
 * Plugin 'WEC Assessment' for the 'wec_assessment' extension.
 *
 * @author	Web-Empowered Church Team <developer@webempoweredchurch.org>
 * @package	TYPO3
 * @subpackage	tx_wecassessment
 */
class tx_wecassessment_pi1 extends tslib_pibase {
	var $prefixId = 'tx_wecassessment_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_wecassessment_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey = 'wec_assessment';	// The extension key.
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content,$conf)	{		
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		$this->pi_initPIflexform();
		$this->pi_USER_INT_obj = 1;
		
		$assessmentClass = t3lib_div::makeInstanceClassName('tx_wecassessment_assessment');
		$this->assessment = new $assessmentClass(0, $GLOBALS['TSFE']->id, $conf, $this->cObj->data['pi_flexform']);
		$this->result = &$this->assessment->getResult();
		
		/* Set variables from TS and flexform */
		/* @todo	Merge with flexforms */
		//$this->assessment->setQuestionsPerPage(intval($this->conf['questionsPerPage']));
		//$this->assessment->setPaging(intval($this->conf['usePaging']));
		
		/* If we're using paging, figure out the page number.  Otherwise, its always page 1 */
		if($this->assessment->usePaging()) {
			$this->assessment->setPageNumber(($this->piVars['nextPageNumber']) ? $this->piVars['nextPageNumber'] : 1);
		} else {
			$this->assessment->setPageNumber(1);
		}
		
		if($this->piVars['answers']) {
			/* Add the newly posted answers to the result and save */
			$this->result->addAnswersFromPost($this->piVars['answers']);
			$this->result->save();
		} else {
			/* If we need to restart, remove the answers and save */
			if($this->piVars['restart']) {
				$this->result->reset();
			}
			
			/**
			 * If we don't have answers, we're in results view or on the first
			 * page of a multi-page assessment.
			 */
			$this->firstLoad = true;
		}	

		/* If the result is complete, show responses.  Otherwise, show questions. */
		if($this->result->isComplete()) {
			$view = "displayResponses";
		} else {
			$view = "displayQuestions";
		}
		
		$this->initTemplate($view);
		$content = $this->$view();
		return $this->pi_wrapInBaseClass($content);
	}
	
	
	/**
	 * Template initializaation function.
	 *
	 * @param		string		Name of the view to initialize.  Current
	 *							options are displayQuestions and displayResponses.
	 * @return		none
	 */
	function initTemplate($view) {
		$utilClass = t3lib_div::makeInstanceClassName('tx_wecassessment_util');
		$this->util = new $utilClass($this);
		$this->util->loadTemplate($view, $this->conf['templateFile']);
	}
	
	
	/**
	 * Displays the HTML markup for all questions on the current page.
	 *
	 * @return	The HTML markup for the current page of questions.
	 */	
	function displayQuestions() {
		$markers = array();
		$subparts = array();	
		$questionHTML = array();
		
		$GLOBALS['TSFE']->additionalHeaderData['prototype'] = '<script src="typo3/contrib/prototype/prototype.js" type="text/javascript"></script>';
		$GLOBALS['TSFE']->additionalHeaderData['scriptaculous_effects'] = '<script src="typo3/contrib/scriptaculous/effects.js" type="text/javascript"></script>';
		$GLOBALS['TSFE']->additionalHeaderData['wec_assessment_pi1'] = '<script src="'.t3lib_extMgm::siteRelPath('wec_assessment').'pi1/assessment.js" type="text/javascript"></script>';
		
		$questions = &$this->assessment->getQuestionsInPage();
		$content = &$this->util->getTemplate();
		
		/**
		 * If we already have some answers and we're on the first page, then
		 * the user has started the assessment before.
		 */
		if($this->result->hasAnswers() and $this->firstLoad) {
			$markers['welcome_back'] = $this->displayWelcomeBack();
		} else {
			$markers['welcome_back'] = '';
		}
		
		if($this->assessment->usePaging()) {
			$markers['progress_bar'] = $this->displayProgressBar();
		} else {
			$markers['progress_bar'] = '';
		}
		
		$markers['post_url'] = t3lib_div::getIndpEnv('TYPO3_REQUEST_URL');
		$markers['next_page_number'] = $this->assessment->getNextPageNumber();

		/* Display editing form for each question */
		if(is_array($questions)) {
			foreach($questions as $question) {
				$questionHTML[] = $this->displayQuestion($question);
			}
		}	
		$subparts['questions'] = implode(chr(10), $questionHTML);
		$this->util->substituteMarkersAndSubparts($content, $markers, $subparts);

		return $content;
	}
	
	function displayProgressBar() {
		$progress = $this->util->getOutputFromCObj($this->assessment->toArray(), $this->conf, 'progress');
		return $progress;
	}
	

	
	/**
	 * Displays the HTML markup for a single question.
	 *
	 * @param		object		The object representing the current question.
	 * @return		string		The HTML markup for the current question.
	 */
	function displayQuestion($question) {
		$markers = array();
		$subparts = array();
		
		$currentAnswer = $this->result->lookupAnswer($question);
		
		$markers['question'] = $this->util->getOutputFromCObj($question->toArray(), $this->conf['questions.'], 'question');
		$subparts['scale'] = $this->displayScale($question, $currentAnswer);

		$content = $this->util->getTemplateSubpart('questions');	
		$this->util->substituteMarkersAndSubparts($content, $markers, $subparts);
		$this->util->wrap($content, $this->conf['questions.']['stdWrap.']);
				
		return $content;
	}
	
		
	/**
	 * Displays the scale of possible answers.
	 *
	 * @param		object		The question to be answered.
	 * @param		object		The existing answer, if it exists.
	 */	
	function displayScale($question, $answer=null) {	
		$answerSet = $this->assessment->getAnswerSet();
		if(is_array($answerSet)) {
			foreach($answerSet as $value => $label) {
				$content = $this->util->getTemplateSubpart('scale');
				$dataArray = array('uid' => $value, 'label' => $label, 'questionUID' => $question->getUID());
				
				/* Set the radio button with the existing answer */
				if($answer && ($answer->getValue() == $value)) {
					$dataArray["checked"] = 'checked="checked"';
				}
				$markers['scale_item'] = $this->util->getOutputFromCObj($dataArray, $this->conf['questions.'], "scaleItem");
				$this->util->substituteMarkers($content, $markers);
				
				$scaleItems[] = $content;
			}
		}
		
		return implode(chr(10), $scaleItems);
	}

	/**
	 * Displays the HTML markup for all responses.
	 *
	 * @return	The HTML markup for all responses.
	 */
	function displayResponses() {
		$responseHTML = array();
		$markers = array();
		$subparts = array();
		
		$content = $this->util->getTemplate();
		$responses = &$this->assessment->calculateAllResponses();
		
		if(is_array($responses)) {
			foreach($responses as $response) {
				$responseHTML[] = $this->displayResponse($response);
			}
		}
		
		$subparts['responses'] = implode(chr(10), $responseHTML);
		$markers['restart'] = $this->displayRestart();

		$this->util->substituteMarkersAndSubparts($content, $markers, $subparts);

		return $content;
		
	}

	/**
	 * Displays the HTML markup for an individual reponse.
	 *
	 * @return The HTML markup for one reponse.
	 */
	function displayResponse($response) {
		$markers = array();
		$markers['response'] = $this->util->getOutputFromCObj($response->toArray(), $this->conf['responses.'], 'response');
		
		$content = $this->util->getTemplateSubpart('responses');
		$this->util->substituteMarkers($content, $markers);
		
		return $content;
	}
	
	/**
	 * Displays the "Welcome Back" message when a user comes back to the assessment
	 * page and we have a stored, incomplete assessment result.
	 *
	 * @return		string		HTML for the "Welcome Back" message.
	 */
	function displayWelcomeBack() {
		$content = array();
		
		$content[] = '<style type="text/css">

		</style>';
		
		$content[] = '<div id="wec-assessment-alert">';
		$content[] = '<h3>'.$this->pi_getLL('questionRestartTitle').'</h3>';
		$content[] = '<p>'.$this->pi_getLL('questionRestartMessage').'</p>';
					
		$content[] = '<form action="'.t3lib_div::getIndpEnv('TYPO3_REQUEST_URL').'" method="post">';
		$content[] = '<input type="hidden" name="tx_wecassessment_pi1[restart]" value="1" />';
		$content[] = '<input type="submit" value="'.$this->pi_getLL('restartButton').'" />';
		$content[] = '</form>';
		$content[] = '</div>';
		
		return implode(chr(10), $content);
	}
	
	function displayRestart() {
		$content = array();
		
		$content[] = '<div id="wec-assessment-alert">';
		$content[] = '<p>'.$this->pi_getLL('responseRestartMessage').'</p>';
					
		$content[] = '<form action="'.t3lib_div::getIndpEnv('TYPO3_REQUEST_URL').'" method="post">';
		$content[] = '<input type="hidden" name="tx_wecassessment_pi1[restart]" value="1" />';
		$content[] = '<input type="submit" value="'.$this->pi_getLL('restartButton').'" />';
		$content[] = '</form>';
		$content[] = '</div>';
		
		return implode(chr(10), $content);
	}
		
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/pi1/class.tx_wecassessment_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/pi1/class.tx_wecassessment_pi1.php']);
}

?>