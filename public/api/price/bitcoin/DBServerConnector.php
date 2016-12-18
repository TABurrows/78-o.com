<?php
require __DIR__.'/lib/rb/rb.php'; 
require('DBConnectionInterface.php');

class DBFileConnector implements DBConnectionInterface {
  private $resultStatusLevel = 'INFO';
  private $resultStatusMessage = 'New Data Connection Created';
  private $results = [];

  private $sel = 'SELECT ask, bid, total_vol, last, p24h_avg, timestamp FROM';
  private $tbl = 'GBP';
  private $ord = 'ORDER BY key DESC';
  private $lmt = 'LIMIT 10';

  private $DBType = 'SQLITE3';
  private $DBLocation = './.htBitcoin.db';
  private $DBReadWrite = SQLITE3_OPEN_READONLY;
  private $DBFetchType = SQLITE3_ASSOC;

  public function __construct($tstamp = null, $limit = null, $desc = false){
  
    $DBData = [];
    $db = null;
    $stmt = null;
    try {
      $db = new SQLite3($this->DBLocation,$this->DBReadWrite);
      if(isset($limit) && $limit > 0 && $limit < 101) {
        $this->lmt = 'LIMIT '.$limit;
      }
      if(is_a($tstamp, 'DateTime')) {
        $tstamp->setTimezone(new DateTimeZone('UTC'));
        $ts1 = "".$tstamp->format('D, d M Y H')."%";

        if(is_numeric($limit) && $limit > 0 && $limit < 101) {
          $offset = $limit - 1;
        } else {
          $offset = 99;
        }
        $stmt1 = 'SELECT ask, bid, total_vol, last, p24h_avg, timestamp FROM GBP WHERE key BETWEEN (SELECT key FROM GBP WHERE';
        if($desc) {
          $stmt2 = 'LIMIT 1) AND ((SELECT key FROM GBP WHERE';
          $stmt3 = 'LIMIT 1) + '.$offset.' )';
        } else  {
          $stmt2 = 'LIMIT 1) - '.$offset.' AND ((SELECT key FROM GBP WHERE';
          $stmt3 = 'LIMIT 1)  )';
        }
        $stmt = $db->prepare($stmt1." timestamp like ? ".$stmt2." timestamp like ? ".$stmt3);
        $stmt->bindValue(1, $ts1, SQLITE3_TEXT);
        $stmt->bindValue(2, $ts1, SQLITE3_TEXT);
        $stmt->bindValue(3, $offset, SQLITE3_TEXT);
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

  public function connect() {
    
  }

  public function getData() {
    return $this->results;
  }
}
?>
