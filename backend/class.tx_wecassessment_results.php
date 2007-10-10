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

require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_answer.php');
require_once(t3lib_extMgm::extPath('wec_assessment').'model/class.tx_wecassessment_question.php');


class tx_wecassessment_results {
	
	function displayResults($PA, $fobj) {
		$pid = $PA['row']['pid'];
		$result_id = $PA['row']['uid'];
		$answers = tx_wecassessment_answer::findAll($pid, "result_id=".$result_id);
		
		$output = array();
		foreach($answers as $answer) {
			$question = $answer->getQuestion();
			
			$output[] = '<tr class="class-main12">
							<td><span class="nbsp">&nbsp;</span></td>
							<td width="99%"><span style="color:;" class="class-main14"><b>'.$question->getText().'</b></span></td>
						</tr>';
						
			$output[] = '<tr class="class-main11">
							<td nowrap="nowrap"><img src="clear.gif" width="10" height="10" alt="" />'.$answer->getValue().'</td>
						</tr>';			
			
		}
				
		return implode(chr(10), $output);
	}
	
	function displayHiddenRecommendationType($PA, $fobj) {
		$name = $PA['itemFormElName'];
		$value = $PA['itemFormElValue'];
		
		return '<input type="hidden" name="'.$name.'" value="'.$value.'" />';
	}
	
	function displayAverageForQuestion($PA, $fobj) {
		$pid = intval($PA['row']['pid']);
		$table = $PA['table'];
		if ($pid < 0)	{
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('pid', $table, 'uid='.abs($pid));
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

			$pid = intval($row['pid']);
		}
		
		$assessmentClass = t3lib_div::makeInstanceClassName('tx_wecassessment_assessment');
		$assessment = new $assessmentClass(0, $pid);
		
		$uid = $PA['row']['uid'];
		if(!strstr($uid,'NEW')) {
			$question = &tx_wecassessment_question::find($uid);

			$averageScore = $question->getAverageAnswer();
			$totalAssessments = $question->getTotalAnswers();
		} else {
			$averageScore = 0;
			$totalAssessments = 0;
		}
		$maximumScore = $assessment->getMaximumValue();
		$percentScore = round(($averageScore / $maximumScore) * 100);
		
		
		$output = array();
		$output[] = '<div style="margin-top: 5px; border: 1px solid black; width:100px;">';
		$output[] = '	<div style="background-color:#888; width:'.$percentScore.'%;">&nbsp;</div>';
		$output[] = '</div>';
		
		$output[] = '<p style="margin-top:5px;">'.$averageScore.' / '.$maximumScore.' (based on '.$totalAssessments.' completed assessments)';
		
		return implode(chr(10), $output);
	}
	
	function displayAverageForCategory($PA, $fobj) {
		/*
		$assessmentClass = t3lib_div::makeInstanceClassName('tx_wecassessment_assessment');
		debug($config, "config");
		$assessment = new $assessmentClass(0, $config['row']['pid']);
		
		$uid = $PA['row']['uid'];
		$category = &tx_wecassessment_category::find($uid);
		
		$averageScore = $category->getAverageAnswer();
		$maximumScore = $assessment->getMaximumValue();
		$totalAssessments = $category->getTotal();
		$percentScore = round(($averageScore / $maximumScore) * 100);
		
		$output = array();
		$output[] = '<div style="margin-top: 5px; border: 1px solid black; width:100px;">';
		$output[] = '	<div style="background-color:#888; width:'.$percentScore.'%;">&nbsp;</div>';
		$output[] = '</div>';
		$output[] = '<p style="margin-top:5px;">'.$averageScore.' / '.$maximumScore.' (based on '.$totalAssessments.' assessments)';
		*/
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/backend/class.tx_wecassessment_results.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/backend/class.tx_wecassessment_results.php']);
}

?>