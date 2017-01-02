<?php
require("DBConnector.php");

class DBServerConnector implements DBConnector {
     private $_dsn, $_dbh, $_dbr;

    public function __construct($requestResult){
        $this->_dbr = $requestResult;
        $this->connect();
    }

    public function connect(){
        $this->_dsn = "mysql:host=localhost;dbname=BITCOIN";
        $this->_dbh = new PDO($this->_dsn, 'redbean', get_cfg_var('redbean'));
        $this->_dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, TRUE);
        $this->_dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function executeQuery($sqlQuery){
        //REMOVE - for debugging only
        error_log("SQLQuery in DBServerConnetor: ".$sqlQuery);
        $_prepared = $this->_dbh->prepare($sqlQuery);
        $_prepared->setFetchMode(PDO::FETCH_OBJ);
        $_prepared->execute();
        $this->_dbr->results = $_prepared->fetchAll();
        return $this->_dbr;
    }
}