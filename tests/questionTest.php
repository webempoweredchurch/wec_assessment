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
class questionTest extends PHPUnit_Framework_Testcase  {

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
		require_once(t3lib_extMgm::extPath('wec_assessment') . 'model/class.tx_wecassessment_question.php');
		
	    $categoryClass = t3lib_div::makeInstanceClassName('tx_wecassessment_category');
		$category = new $categoryClass(1, 0, 'My Title', 'My Description', 'image.jpg', '0');
		
		$questionClass = t3lib_div::makeInstanceClassName('tx_wecassessment_question');
		$this->question = new $questionClass(1, 0, 0, "This is my question text.", 0, 10);
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @access protected
     */
    protected function tearDown() {
    }
	
	public function testText() {
        $this->assertEquals("This is my question text.", $this->question->getText());
	}
	
	public function testWeight() {
		$this->assertEquals(10, $this->question->getWeight());
	}

    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main() {
        global $class;
        require_once "PHPUnit/TextUI/TestRunner.php";
        $suite  = new PHPUnit_Framework_TestSuite($class);
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

}


?>
