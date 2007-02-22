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

require_once('include.php');
require_once(t3lib_extMgm::extPath('wec_assessment') . 'model/class.tx_wecassessment_result.php');
require_once(t3lib_extMgm::extPath('wec_assessment') . 'model/class.tx_wecassessment_category.php');

/**
 * Test class for tx_xxx
 */
class exampleTest extends PHPUnit_Framework_Testcase  {

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
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @access protected
     */
    protected function tearDown() {
    }
	
	public function testSomething() {
		// Create the Array fixture.
		
	    $result = tx_wecassessment_result::findCurrent(1);

	        // Assert that the size of the Array fixture is 0.
	        $this->assertEquals(0, sizeof($fixture));
	}
	
    /**
     * Load extension by key
     *
     * @access protected
     * @par    string         extension key
     * @return void    
     * 
     */
    protected function load($key){
        if(is_dir(PATH_site . 'typo3conf/ext/' . $key . '/')) {
            $GLOBALS['TYPO3_LOADED_EXT']['' . $key . '']['siteRelPath']
                = 'typo3conf/ext/' . $key . '/';
        }elseif(is_dir(PATH_site . 'typo3/ext/' . $key . '/')) {
            $GLOBALS['TYPO3_LOADED_EXT']['' . $key . '']['siteRelPath'] 
                = 'typo3/ext/' . $key . '/';
        }elseif(is_file(PATH_site . 'typo3/sysext/' . $key . '/')) {
            $GLOBALS['TYPO3_LOADED_EXT']['' . $key . '']['siteRelPath']
                = 'typo3/sysext/' . $key . '/';
        }else{
            exit(chr(10) . 'Unknown installation path for ' . $key . '');
        }
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
