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
class categoryTest extends PHPUnit_Framework_Testcase  {

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
		require_once(t3lib_extMgm::extPath('wec_assessment') . 'model/class.tx_wecassessment_response.php');
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @access protected
     */
    protected function tearDown() {
    }
		
	/**
	 *  Tests
	 */
	
	public function testNoInitialResponses() {
		$categoryClass = t3lib_div::makeInstanceClassName('tx_wecassessment_category');
		$category = new $categoryClass(1, 0, 'My Title', 'My Description', 'image.jpg', 0);
		$GLOBALS['TYPO3_DB'] = $this->getMock('t3lib_DB', array('exec_SELECTquery', 'sql_fetch_assoc'));
		$GLOBALS['TYPO3_DB']->expects($this->any())
							->method('exec_SELECTquery')
							->will($this->returnValue(array()));
		$GLOBALS['TYPO3_DB']->expects($this->any())
							->method('sql_fetch_assoc')
							->will($this->returnValue(array()));

		$this->assertEquals(count($category->getResponses()), 0);
	}
	
	public function testSetResponsesRightUID() {
		
		$categoryClass = t3lib_div::makeInstanceClassName('tx_wecassessment_category');
		$category = new $categoryClass(1, 0, 'My Title', 'My Description', 'image.jpg', 0);
	
		// get response class
		$responseClass = t3lib_div::makeInstanceClassName('tx_wecassessment_response');
		
		// create two responses, both with category uid 1
		$response1 = new $responseClass(1, 0, 'Text 1', 0, 10, 2);
		$response2 = new $responseClass(2, 0, 'Text 2', 11, 20, 2);

		// now add both responses to category with uid 0
		$category->setResponses(array($response1, $response2));

		// get responses back
		$responses = $category->getResponses();
		
		// make sure that the responses now have category uid 0 set
		foreach( $responses as $response) {
			$this->assertEquals($response->getCategoryUID(), 1);
		}
	}
	
	public function testResponseOverlap() {
		$categoryClass = t3lib_div::makeInstanceClassName('tx_wecassessment_category');
		$category = new $categoryClass(1, 0, 'My Title', 'My Description', 'image.jpg', 0);
				
		// get response class
		$responseClass = t3lib_div::makeInstanceClassName('tx_wecassessment_response');
		
		// create two responses
		$response1 = new $responseClass(1, 0, 'Text 1', 0, 11, 1);
		$response2 = new $responseClass(2, 0, 'Text 2', 10, 20, 1);
		
		// now add both responses to category
		$category->setResponses(array($response1, $response2));

		// test for overlap - make sure category is not valid
		$this->assertEquals($category->valid(0,20), false);

	}
	
	public function testResponseGap() {
		$categoryClass = t3lib_div::makeInstanceClassName('tx_wecassessment_category');
		$category = new $categoryClass(1, 0, 'My Title', 'My Description', 'image.jpg', 0);
				
		// get response class
		$responseClass = t3lib_div::makeInstanceClassName('tx_wecassessment_response');
		
		// create two responses
		$response1 = new $responseClass(1, 0, 'Text 1', 0, 10, 1);
		$response2 = new $responseClass(2, 0, 'Text 2', 20, 30, 1);
		
		// now add both responses to category
		$category->setResponses(array($response1, $response2));

		// test for overlap - make sure returned array is not 0
		$this->assertEquals($category->valid(0,30), false);

	}

}


?>
