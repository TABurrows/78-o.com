<?php

class DataRequester {
  private $DBDataRequestResult = ['empty'];
  private $DBDataRequestStatus = '';
  private $DBDataRequestMessage = '';

  public function __construct($source = null, $startDate = null, $startHour = null, $limit = null) {
    try {
      $tz = new DateTimeZone('Europe/London');
      $tstamp = null;
      if($startDate) {
          $tstamp = new DateTime($startDate);
          $tstamp->setTimezone($tz);
        }
      if($startHour) {
        if(!is_a($tstamp, 'DateTime')) {
          $tstamp = new DateTime('now');
          $tstamp->setTimezone($tz);
        }
        $tstamp->setTime($startHour, 0, 0);
      }
    switch($source) {
      case 'MYSQL':
        require('DBServerConnector.php');
        $DBConnection = new DBServerConnection($tstamp, $limit);
        $this->DBDataRequestResult = $DBConnection->getData();
        break;
      default:
        require('DBFileConnector.php');
        $DBConnection = new DBFileConnector($tstamp, $limit);
        $this->DBDataRequestResult = $DBConnection->getData();
        break;
      }
    } catch (Exception $e) {
      $this->$DBDataRequestStatus = 'ERROR';
      $this->$DBDataRequestSMessage = 'Caught exception whilst trying to build data request ' + $e->message;
    }
  }

  public function getJSON() {
    return json_encode($this->DBDataRequestResult, JSON_PRETTY_PRINT);
  }
} ?>
