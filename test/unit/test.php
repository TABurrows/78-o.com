<?php
require("DataRequestResponseTest.php");
require("ToolsTest.php");

$test = new DataRequestResponseTest( "test_statuslevel" );
$testRunner = new TestRunner();
$testRunner->run( $test );

$test = new ToolsTest("test_dateIsInvalidOrNotBetween");
$testRunner = new TestRunner();
$testRunner->run( $test );

?>
