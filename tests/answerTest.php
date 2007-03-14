<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2007 Web-Empowered Church Team
 *  Contact: info@webempoweredchurch.org
 *  All rights reserved
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 ***************************************************************/

require_once 'PHPUnit/Framework.php';
require_once('initTYPO3.php');

/**
 * Test class for tx_xxx
 */
class answerTest extends PHPUnit_Framework_Testcase  {

    /****************************************************************
     * main, setUP, tearDown
     ****************************************************************/

     public function __construct ($name) {
          parent::__construct ($name);
     }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @access protected
     */
    protected function setUp() {
		require_once(t3lib_extMgm::extPath('wec_assessment') . 'model/class.tx_wecassessment_answer.php');
		require_once(t3lib_extMgm::extPath('wec_assessment') . 'model/class.tx_wecassessment_question.php');
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @access protected
     */
    protected function tearDown() {
    }

	public function test_toArray() {
		$answerClass = t3lib_div::makeInstanceClassName('tx_wecassessment_answer');
		$answer = new $answerClass(1,0,'Answer', 0,0);
		
		$expectedArray = array('uid' => 1, 'pid' => 0, 'value' => 'Answer', 'question_id' => 0, 'result_id' => 0);
		
		$this->assertEquals($answer->toArray(), $expectedArray);
		
	}
	
	public function test_getWeightedValue() {

		// create answer and question objects
		$answerClass = t3lib_div::makeInstanceClassName('tx_wecassessment_answer');
		$answer = new $answerClass(1,0,'Answer', 0,0);
		
		$questionClass = t3lib_div::makeInstanceClassName('tx_wecassessment_question');
		$question = new $questionClass(1, 0, 1, 'Question', 0, 20);
		
		$answer->setQuestion($question);
		
		$this->markTestIncomplete();
	}
}


?>
