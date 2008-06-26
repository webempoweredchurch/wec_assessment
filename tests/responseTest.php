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
class recommendationTest extends PHPUnit_Framework_Testcase  {

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
		// require_once(t3lib_extMgm::extPath('wec_assessment') . 'model/class.tx_wecassessment_question.php');
		require_once(t3lib_extMgm::extPath('wec_assessment') . 'model/class.tx_wecassessment_recommendation.php');
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
	
	public function test_toArray() {
		// get recommendation class
		$recommendationClass = t3lib_div::makeInstanceClassName('tx_wecassessment_recommendation');
		$recommendation = new $recommendationClass(1, 0, 'Text 1', 0, 10, 1);
		
		// create category
		$categoryClass = t3lib_div::makeInstanceClassName('tx_wecassessment_category');
		$category = new $categoryClass(1, 0, 'Title', 'Description', 'image.jpg', 1);
		
		// add category to recommendation
		$recommendation->setCategory($category);
		
		$expectedArray = array('uid' => 1, 'pid' => 0, 'text' => 'Text 1', 'min_value' => 0,
							   'max_value' => 10, 'category' => $category->getTitle());
		
		$this->assertEquals($recommendation->toArray(), $expectedArray);
	}
	
	public function test_validRelativeToPrevious() {
		// get recommendation class
		$recommendationClass = t3lib_div::makeInstanceClassName('tx_wecassessment_recommendation');
		
		// create two recommendations
		$recommendation1 = new $recommendationClass(1, 0, 'Text 1', 0, 10, 1);
		$recommendation2 = new $recommendationClass(2, 0, 'Text 2', 11, 20, 1);
		

		// test they are valid relative to each other
		$this->assertTrue($recommendation2->validRelativeTo($recommendation1));
	}
	
	public function test_checkRange() {
		// get recommendation class
		$recommendationClass = t3lib_div::makeInstanceClassName('tx_wecassessment_recommendation');
		
		// create two recommendations
		$recommendation1 = new $recommendationClass(1, 0, 'Text 1', 0, 10, 1);
		$recommendation2 = new $recommendationClass(2, 0, 'Text 2', 10, 0, 1);
		
		$this->assertEquals($recommendation1->getMinValue(), 0);
		$this->assertEquals($recommendation1->getMaxValue(), 10);
		$this->assertEquals($recommendation2->getMinValue(), 10);
		$this->assertEquals($recommendation2->getMaxValue(), 0);

		// test for overlap
		$this->assertTrue($recommendation1->checkRange());
		$this->assertFalse($recommendation2->checkRange());
	}

}


?>
