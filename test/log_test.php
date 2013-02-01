<?php 
require_once (dirname(__FILE__) . 'simpletest/autorun.php');

class TestOfLogging extends UnitTestCase {
	function testFirstLogMessageCreatesFileIfNonexistent() {
		@unlink(dirname(__FILE__) . 'temp/test.log');
		$log = new Log(dirname(__FILE__) . 'temp/test.log');
		$log->message('Should write to this file');
		$this->assertTrue(file_exists(dirname(__FILE__) . 'temp/test.log'));
		
	}
}

?>