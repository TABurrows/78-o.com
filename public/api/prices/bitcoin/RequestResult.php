<?php
require("DBServerConnector.php");
require("SQLQueryBuilder.php");


class RequestResult{
    public $status = [];
    public $defaults = [];
    public $params = [];
    public $results = [];

         public function __construct($dataRequest){
             $this->status = $dataRequest->status;
             $this->defaults = $dataRequest->defaults;
             $this->params = $dataRequest->params;
         }

         public function executeRequest() {
            $sqlQuery = new SQLQueryBuilder($this->params);
            $sqlString = $sqlQuery->getSQLString('GBP');
            $sqlStmt = $sqlString;
            //REMOVE THIS LINE - ONLY FOR DEBUGGING
            //$this->status['query'] = (string)$sqlString;
            $serverConnector = new DBServerConnector($this);
            return $serverConnector->executeQuery($sqlString);
         }
}