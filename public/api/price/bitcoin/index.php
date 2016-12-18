<?php
require('DataRequester.php');

$cleanStartDate = null;
$cleanStartHour = null;
$cleanLimit = null;
$cleanJSONPCallback = null;

$validStartDateValue = false;
$validStartHourValue = false;
$validLimitValue = false;
$validJSONPCallback = false;

$results = [];
$jsonpRequest = false;

try {
  //Test startDate
  $cleanStartDate = filter_input(
                          INPUT_GET,
                          'startDate',
                          FILTER_VALIDATE_REGEXP,
                          ['options' =>
                              ['regexp' => '/^\d{4}-\d{1,2}-\d{1,2}$/']
                          ]);
  if(isset($cleanStartDate)) {
    if($cleanStartDate){
      $dtmEra = new DateTime('now');
      $dtmEpoch = new DateTime('2016-07-10');
      $dtmTest = new DateTime($cleanStartDate);
      if(($dtmTest >= $dtmEpoch) && ($dtmTest <= $dtmEra)) {
        $validStartDateValue = true;
      } else {
        throw new Exception('It does did not fall within a valid time scale or the form 2016-07-19.');
      }
    } else {
      throw new Exception('The supplied value for startDate does not have the correct form.');
    }
  } else {
    // A null value is valid
    $validStartDateValue = true;
  }
  //Test callback meets '/^[^a-zA-Z0-9_-]$/'
  $cleanJSONPCallback = filter_input(
                                    INPUT_GET,
                                    'callback',
                                    FILTER_VALIDATE_REGEXP,
                                    ['options' =>
                                        ['regexp' => '/^*$/']
                                    ]);
  if(isset($cleanJSONPCallback)) {
      $jsonpRequest = true;
    }
  if($cleanJSONPCallback || is_null($cleanJSONPCallback)) {
      //a clean or null value is valid
      $validJSONPCallback = true;
    } else {
      //throw new Exception('The supplied value for callback does not have the correct form.');
    }
    $cleanJSONPCallback = $_GET['callback'];
  //Test startHour
  $cleanStartHour = filter_input(
                              INPUT_GET,
                              'startHour',
                              FILTER_VALIDATE_INT,
                              ['options' =>
                                  ['min_range'=>0, 'max_range'=>23]
                              ]);
  if($cleanStartHour || is_null($cleanStartHour)){
    $validStartHourValue = true;
  } else {
    throw new Exception('The value supplied for startHour was not valid.');
  }
  //Test limit
  $cleanLimit = filter_input(
                              INPUT_GET,
                              'limit',
                              FILTER_VALIDATE_INT,
                              ['options' =>
                                  ['min_range'=>1, 'max_range'=>101]
                              ]);
  if($cleanLimit || is_null($cleanLimit)){
    $validLimitValue = true;
    //Test Orderer
    $cleanDesc = filter_input(
                  INPUT_GET,
                  'desc',
                  FILTER_SANITIZE_SPECIAL_CHARS);
  if(isset($cleanDesc))
    $cleanDesc = true;
  else
    $cleanDesc = false;
                
  } else {
    throw new Exception('The value supplied for limit was not valid.');
  }
  //Read key GET variable if present
  $cleanKey = filter_input(
    INPUT_GET,
    'key',
                                    FILTER_VALIDATE_REGEXP,
                                    ['options' => ['regex' => '/^\d$/']]);
  //  if(isset($cleanKey))

  

// -------------------------------------------------------------------------------
} catch (Exception $e) {
  $lastStatusLevel = 'ERROR';
  $lastStatusLevel = 'Invalid request parameter found';
}






if($validStartDateValue || $validStartHourValue || $validLimitValue || $validJSONPCallback){
  $req = new DataRequester(null, $cleanStartDate, $cleanStartHour, $cleanLimit, $cleanDesc);
  $results = $req->getDATA();
} else {
  $results['status'] = [$lastStatusLevel => $lastStatusMessage];
}

if($jsonpRequest){
  header('Content-type: application/javascript; charset=utf-8');
  header('X-Powered-By: 14 drunk monkeys');
  echo $cleanJSONPCallback."(".json_encode($results).")";
}else {
  header('Content-type: application/json; charset=UTF-8');
  header('X-Powered-By: 14 drunk monkeys');
  echo json_encode($results, JSON_PRETTY_PRINT);
}



 ?>
