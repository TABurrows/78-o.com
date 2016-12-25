<?php
echo( dirname(dirname(dirname(__DIR__))).'/lib/rb.php');


class DataSet {

    public function __construct() {
       return R::setup('mysql:host=localhost;dbname=BITCOIN','redbean', getenv('REDBEAN'));
    }

    public function getMostRecent($quantity = 6) {
        return R::getAll('SELECT id ask, bid, total_vol, last, p24h_avg, timestamp FROM GBP LIMIT :quantity');
    }
    
    public function getOne(){
        return R::getAll('SELECT id ask, bid, total_vol, last, p24h_avg, timestamp FROM GBP LIMIT 1');
    }
}
