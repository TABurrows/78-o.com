<?php

require_once (dirname(dirname(__DIR__))."/public/api/price/bitcoin-v2/tools.php");

use PHPUnit\Framework\TestCase;

Class ToolsTest extends TestCase {
   function test_dateIsInvalidOrNotBetween() {
      /* Setup for tests against dateIsInvalidOrNotBetween */
      $dateStringForStart = '2016-7-12';
      $dateStringForEnd = 'now';
      /* Reporting testing stage */
      echo PHP_EOL.PHP_EOL."Testing tools/dateIsInvalidOrNotBetween ... ";

      /* First test: valid date
       * $result should be false
       */
      $dateStringToTest = '2016-07-13';
      $result = dateIsInvalidOrNotBetween($dateStringToTest, $dateStringForStart, $dateStringForEnd);
      if($result)
        $this->fail('TEST FAILED: startDate '. $dateStringToTest . ' should pass as it is in the expected range');


      /* 2nd test: date supplied is too early
       * $result should be true
       */
      $dateStringToTest = '2016-07-11';
      $result = dateIsInvalidOrNotBetween($dateStringToTest, $dateStringForStart, $dateStringForEnd);
      if(!$result)
        $this->fail('TEST FAILED: Function is returning false for the test of startDate '. $dateStringToTest . ' being in the range of '. $dateStringForStart.' and '.$dateStringForEnd);


      /* 3rd test: date string supplied can not be parse into a DateTime Ojbect
       * $result should be true
       */
      $dateStringToTest = '2016-13-11';
      $result = dateIsInvalidOrNotBetween($dateStringToTest, $dateStringForStart, $dateStringForEnd);
      if(!$result)
        $this->fail('TEST FAILED: Function is returning true for the casting of startDate '. $dateStringToTest . ' into a valid DateTime object without exception.');


      /* 4th test: date string supplied has the unsupported format of day chars before the month chars
       * $result should be true
       */
      $dateStringToTest = '2016-30-7';
      $result = dateIsInvalidOrNotBetween($dateStringToTest, $dateStringForStart, $dateStringForEnd);
      if(!$result)
        $this->fail('TEST FAILED: Function is returning true for the casting of startDate '. $dateStringToTest . ' into a valid DateTime object where the DAY CHARS are before the MON CHARS.');




      /* 5th test: date string supplied is not a date string
       * $result should be true
       */
      $dateStringToTest = '9999-99-99';
      $result = dateIsInvalidOrNotBetween($dateStringToTest, $dateStringForStart, $dateStringForEnd);
      if(!$result)
        $this->fail('TEST FAILED: Function is returning true for the casting of startDate '. $dateStringToTest . ' into a valid DateTime object where the string is representative of a datetime.');













    echo "done.".PHP_EOL;
   }

};

?>
