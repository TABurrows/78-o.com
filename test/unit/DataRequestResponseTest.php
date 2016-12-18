<?php
//$rootDir = dirname(dirname(__DIR__));
//$rootDir = $rootDir.str_replace('test/unit','',);
//echo $rootDir;
require_once (dirname(dirname(__DIR__))."/public/api/price/bitcoin-v2/DataRequestResponse.php");
//require_once (dirname(dirname(__DIR__))."/bin/php/phpunit");

use PHPUnit\Framework\TestCase;

Class DataRequestResponseTest extends TestCase {
   function test_StatusLevel() {
      /* Reporting testing stage */
      echo PHP_EOL.PHP_EOL."Testing DataRequestResponse/StatusLevel ... ";

      $testClass = new DataRequestResponse([]);
      $expected = '';
      $result = $testClass->StatusLevel();
      $this->assertEquals($expected, $result, 'First test for empty string');

      echo "done.".PHP_EOL;
   }

};

?>
