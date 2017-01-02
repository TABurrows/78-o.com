<?php

class DataRequest{

    //Set the Status of the request
    public $status = [];
    public $params = [];

    //Set the Default parameter values
    public $defaults = [
        'select' => 'ask|bid|total_vol|last|p24h_avg|timestamp',
        'limit' => 5,
        'order' => 'DESC',
        'by' => 'id',
        'preferreddb' => 'SQLITE'
    ];


    //return the Data Request defaults
    // on construction
    public function __construct($HTTPVerb) {
        $this->status['requestType'] = $HTTPVerb;
    }

}