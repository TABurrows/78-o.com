<?php
require( dirname(dirname(dirname(__DIR__))).'/lib/rb.php');


class DataSet {

    private $DBData = [];

    public function __construct() {
       return R::setup('mysql:host=localhost;dbname=BITCOIN','redbean', get_cfg_var('redbean'));
    }

    public static function getMostRecent($quantity = 6) {
        return R::getAll('SELECT id, ask, bid, total_vol, last, p24h_avg, timestamp FROM GBP LIMIT :quantity');
    }
    
    public static function getOne(){
        $DBData['results'] = R::getAll('SELECT id, ask, bid, total_vol, last, p24h_avg, timestamp FROM GBP ORDER BY id DESC LIMIT 1') ;
        $DBData['recordCount'] = (string)count($DBData['results']);
        $DBData['status'] = ["level"=>"INFO", "messages"=>"Successfully collected " . $DBData['recordCount'] . " records" ];
        return $DBData;
    }
}
