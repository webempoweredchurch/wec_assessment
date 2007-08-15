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
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/backend/class.tx_wecassessment_results.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/backend/class.tx_wecassessment_results.php']);
}

?>