<?php

class DataRequester {
  private $DBDataRequestResult = ['empty'];
  private $DBDataRequestStatus = '';
  private $DBDataRequestMessage = '';

  public function __construct($source = null, $startDate = null, $startHour = null, $limit = null) {
    try {
      //The default db query is quickest when searches occur
      //on a key rather than a date object
      $tstamp = null;
      $tz = new DateTimeZone('Europe/London');
      if(isset($startDate)) {
        $tstamp = new DateTime($startDate);
      }
      if(isset($startHour)) {
        if(is_null($tstamp)){
          $tstamp = new DateTime('now');
        }
        $tstamp->setTime($startHour, 0, 0);
        $tstamp->setTimeZone($tz);
      }
      switch($source) {
        case 'MYSQL':
          require('DBServerConnector.php');
          $DBConnection = new DBServerConnection($tstamp, $limit);
          $this->DBDataRequestResult = $DBConnection->getData();
          break;
        case 'SQLITE3':
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

  public function getDATA() {
    return $this->DBDataRequestResult;
  }
} ?>
