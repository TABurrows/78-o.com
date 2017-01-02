<?php

interface DBConnector {
    public function __construct($requestResult);
    public function connect();
    public function executeQuery($sqlQuery);
}