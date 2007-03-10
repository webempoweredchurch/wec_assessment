<?php
require_once 'PHPUnit/Framework.php';
$globalVar = 'foo';
tester::test('outside');

class bugtest extends PHPUnit_Framework_TestCase
{
    public function testGlobal()
    {
		global $globalVar;
		tester::test('inside');
        $this->assertEquals('foo', $globalVar);
    }
}

class tester {
	
	function test($salt) {
		global $globalVar;
		echo $globalVar . $salt . "\n";
	}
}
?>