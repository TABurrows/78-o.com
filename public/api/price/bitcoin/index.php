<?php
require('DataRequester.php');

$taintedStartDate = $_GET['startDate'];
$taintedStartHour = $_GET['startHour'];
$taintedLimit = $_GET['limit'];

$cleanStartDate = null;
$cleanStartHour = null;
$cleanLimit = null;

$invalidStartDateValue = false;
$invalidStartHourValue = false;
$invalidLimitValue = false;

$lastStatusLevel = 'INFO';
$lastStatusMessage = 'Started testing for presence of valid data';

if($taintedLimit) {
  if(is_numeric($taintedLimit) && $taintedLimit > 0 && $taintedLimit < 101) {
    $cleanLimit = $taintedLimit;
  } else {
    $invalidLimitValue = true;
    $lastStatusLevel = 'ERROR';
    $lastStatusMessage = 'The supplied value for limit was not numeric or in the range 1-100';
  }
}

if($taintedStartHour) {
  if(is_numeric($taintedStartHour) && $taintedStartHour < 24 && $taintedStartHour >= 0){
    $cleanStartHour = $taintedStartHour;
  } else {
    $invalidStartHourValue = true;
    $lastStatusLevel = 'ERROR';
    $lastStatusMessage = 'The supplied value for startHour was not numeric or in a valid 24 hour clock form';
  }
}

if($taintedStartDate) {
  $regexTestForDigitsAndHyphensOnly = filter_input(
                                    INPUT_GET,
                                    'startDate',
                                    FILTER_VALIDATE_REGEXP,
                                    ['options' =>
                                        ['regexp' => '/^\d{4}-\d{1,2}-\d{1,2}$/']
                                    ]);
  if($regexTestForDigitsAndHyphensOnly) {
    try {
      $dtmEra = new DateTime('now');
      $dtmEpoch = new DateTime('2016-07-10');
      $dtmTest = new DateTime($taintedStartDate);
      if(($dtmTest >= $dtmEpoch) && ($dtmTest <= $dtmEra)) {
          $cleanStartDate = $taintedStartDate;
      } else {
        throw new Exception('It does did not fall within a valid time scale or the form 2016-07-19');
      }
    } catch(Exception $e) {
      $invalidStartDateValue = true;
      $lastStatusLevel = 'ERROR';
      $lastStatusMessage = 'The supplied value for startDate was invalid. '.$e->getMessage();
    }

  } else {
    $invalidStartDateValue = true;
    $lastStatusLevel = 'ERROR';
    $lastStatusMessage = 'The supplied value for startDate does not have the correct form.';
  }
}

if($invalidStartDateValue || $invalidStartHourValue || $invalidLimitValue){
  $summary['status'] = [$lastStatusLevel => $lastStatusMessage];
  echo json_encode($summary);
} else {
  $req = new DataRequester(null, $cleanStartDate, $cleanStartHour, $cleanLimit);
  echo $req->getJSON();
}
 ?>
