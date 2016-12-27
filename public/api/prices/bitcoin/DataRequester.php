<?php
require( dirname(dirname(dirname(__DIR__))).'/lib/rb.php');



class DataSet {

    public function __construct() {
       return R::setup('mysql:host=localhost;dbname=BITCOIN','redbean', get_cfg_var('redbean'));
    }

    public function getMostRecent($quantity = 6) {
        return R::getAll('SELECT id, ask, bid, total_vol, last, p24h_avg, timestamp FROM GBP LIMIT :quantity');
    }
    
    public function getOne(){
        //return $this->getMostRecent(1); 
        return R::getAll('SELECT id, ask, bid, total_vol, last, p24h_avg, timestamp FROM GBP ORDER BY id DESC LIMIT 1');
    }
}
