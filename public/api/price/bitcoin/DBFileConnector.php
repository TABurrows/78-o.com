<?php

class DBFileConnector {
  private $resultStatusLevel = 'INFO';
  private $resultStatusMessage = 'New Data Connection Created';
  private $results = [];

  private $sel = 'SELECT * FROM';
  private $tbl = 'GBP';
  private $ord = 'ORDER BY key DESC';
  private $lmt = 'LIMIT 10';

  private $DBType = 'SQLITE3';
  private $DBLocation = __DIR__.'/.htBitcoin.db';
  private $DBReadWrite = SQLITE3_OPEN_READONLY;
  private $DBFetchType = SQLITE3_ASSOC;

  public function __construct($tstamp = null, $limit = null){

    $DBData = [];
    $db = null;
    $stmt = null;
    try {
      $db = new SQLite3($this->DBLocation,$this->DBReadWrite);
      if($limit) {
        $this->lmt = 'LIMIT '.$limit;
      }
      if(is_a($tstamp, 'DateTime')) {
        $tstamp->setTimezone(new DateTimeZone('UTC'));
        $ts = "".$tstamp->format('D, d M Y H')."%";
        if(is_numeric($limit)) {
          $offset = $limit - 1;
        } else {
          $offset = 99;
        }
        $stmt1 = 'select * from GBP where key Between (select key from GBP where';
        $stmt2 = ') and ((select key from gbp where';
        $stmt3 = ')+'.$offset.')';
        $stmt = $db->prepare($stmt1.' timestamp like ? limit 1'.$stmt2.' timestamp like ? limit 1'.$stmt3);
        $stmt->bindValue(1, $ts, SQLITE3_TEXT);
        $stmt->bindValue(2, $ts, SQLITE3_TEXT);
      } else {
        $stmt = $db->prepare($this->sel.' '.$this->tbl.' '.$this->ord.' '.$this->lmt);
      }
      if($stmt) {
        $result = $stmt->execute();
        if($result) {
          $i = 0;
          while($row = $result->fetchArray($this->DBFetchType)) {
            $i++;
            $DBData['Result'.$i] = $row;
          }
          $this->resultStatusLevel = 'INFO';
          $this->resultStatusMessage = 'Successfully collected '.$i.' records';
        } else {
          throw new Exception('Nothing returned from the database for the query '.$this->DBQueryString);
        }
      } else {
        throw new Exception('The Prepared Statement was invalid after binding to the DB with '.$this->DBQueryString);
      }
    } catch (Exception $e) {
        $this->resultStatusLevel = 'ERROR';
        $this->resultStatusMessage = 'Caught an exception whilst accessing the database: '.$e->getMessage();
    }
    $DBData['status'] = [ $this->resultStatusLevel => $this->resultStatusMessage ];
    $this->results = $DBData;
  }

  public function getData() {
    return $this->results;
  }
} 
?>
