<?php
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'AllTests::main');
}
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

$key = 'wec_assessment';   // extension key
$class = 'exampleTest'; // class name
 
//require_once 'Framework_AllTests.php';
// ...
require_once 'exampleTest.php';
 
class AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }
 
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit');
 
        //$suite->addTest(Framework_AllTests::suite());
        // ...
 	
	$suite->addTestSuite('ExampleTest');
	$suite->addTestSuite('QuestionTest');
	
        return $suite;
    }
}
 
if (PHPUnit_MAIN_METHOD == 'AllTests::main') {
    AllTests::main();
}

// Fix part to set after class definition
if(!defined('PATH_site')) { // If running from command line

	// Setup environment
	$path = $_SERVER['PWD'] .'/'. $_SERVER['SCRIPT_NAME'];
	
	if(!preg_match('|(.*)(typo3conf.*)(' . $key . '/tests)|', $path, $matches)) {
		if(!preg_match('|(.*)(typo3/sysext.*)(' . $key . '/tests)|', $path, $matches))
			exit(chr(10) . 'Unknown installation path' . chr(10). $path . chr(10));
    }
 
    define('PATH_site', $matches[1]);
    $GLOBALS['TYPO3_LOADED_EXT'][$key]['siteRelPath']= $matches[2] . $key . '/';
    define('PATH_t3lib', PATH_site . 't3lib/');
    require_once(PATH_t3lib . 'class.t3lib_div.php');
    require_once(PATH_t3lib . 'class.t3lib_extmgm.php');
	
}

?>
