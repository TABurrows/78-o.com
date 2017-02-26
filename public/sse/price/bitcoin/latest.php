<?php

class GetDBFileLatest {

    // Response object variables
    private $resultStatusLevel = '';
    private $resultStatusMessage = '';
    private $resultValues = '';

    // DB Connection variables
    private $DBType = 'SQLITE3';
    private $DBLocation = '../../../api/price/bitcoin/.htBitcoin.db';
    private $DBReadOnly = SQLITE3_OPEN_READONLY;
    private $DBFetchType = SQLITE3_ASSOC;

    // DB Query variables
    private $sel = "SELECT key AS id, ask, bid, total_vol, last, p24h_avg, timestamp FROM GBP ORDER BY KEY DESC LIMIT 2";

    public function __construct() {
        //$DBData = [];
        $DBResults = [];
        $db = null;
        $stmt = null;
        try {
            $db = new SQLite3($this->DBLocation,$this->DBReadOnly);
            $stmt = $db->prepare($this->sel);
            $result = $stmt->execute();
            if($result) {
                $i = 0;
                while($row = $result->fetchArray($this->DBFetchType)) {
                    $DBResults[$i] = $row;
                    $i++;
                }
                $DBData['results'] = $DBResults;
                $DBData['recordCount'] = (string)$i;
                $this->resultStatusLevel = 'INFO';
                $this->resultStatusMessage = 'Successfully collected '.$i.' records';
            } else {
                throw new Exception('No result was returned from the database.');
            }
        } catch(Exception $e) {
            $this->resultStatusLevel = 'ERROR';
            $this->resultStatusMessage = 'Caught an exception whilst accessing the bitcoin price database: '.$e->getMessage();
        }
        $DBData['status'] = [ 'level' => $this->resultStatusLevel, 'messages' => $this->resultStatusMessage ];
        $this->resultValues = $DBData;
     }

     public function getData() {
         return $this->resultValues;
     }

}

$latest = new GetDBFileLatest();
$results = json_encode($latest->getData());

//Now write out the latest values
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

echo "retry: 60000\n";
echo "data: {$results}\n\n";
flush();