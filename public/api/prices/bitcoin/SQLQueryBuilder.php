<?php

class SQLQueryBuilder {

    private $select, $from, $where, $greater, $less, $average, $between, $limit, $orderby;
    private $sqlString, $qualifier;

    public function __construct($params){
        $this->limit = $this->buildLimit($params['limit']);
        $this->select = $this->buildSelect($params['select']);
        $this->where = $this->buildWhere($params['where']);
        $this->less = $this->buildLess($params['less']);
        $this->greater = $this->buildGreater($params['greater']);
        $this->orderby = $this->buildOrderBy($params['by'],$params['order']);
    }

    public function getSQLString($tableName){
        $this->from = " FROM ".$tableName;
        $this->qualifier = $this->workOutPrecedence();
        $this->sqlString = $this->select.
                            $this->from.
                            $this->qualifier.
                            $this->orderby.
                            $this->limit;
        return $this->sqlString;
    }

    private function buildOrderBy($by, $order){
        $o = "";
        if(isset($by)) {
            $o = " ORDER BY ".$by;
            if(isset($order))  
                $o .= " ".$order;
        }
        return $o;
    }

    private function buildSelect($selectInput) {
        //add the 'id' if it isn't there already
        $selectInput = "|".$selectInput."|";
        if(strpos($selectInput, "|id") === false) $selectInput = "id".$selectInput;
        $selectInput = str_replace("|||", "|", $selectInput);
        $selectInput = str_replace("||", "|", $selectInput);
        $selectInput = ltrim($selectInput, "|");
        $selectInput = rtrim($selectInput, "|");
        return "SELECT ".str_replace("|", ", " ,$selectInput);
    }

    private function buildLimit($limitInput){
        return " LIMIT ".$limitInput;
    }

    private function buildWhere($whereInput){
        $w = explode("|", $whereInput);
        if(isset($w[0]) && isset($w[1]))
            return " WHERE ".$w[0]." = ".$w[1];
        else
            return "";
    }

     private function buildGreater($greaterInput){
        $g = explode("|", $greaterInput);
        if(isset($g[0]) && isset($g[1]))
            return " WHERE ".$g[0]." >= ".$g[1];
        else
            return "";
    }

    private function buildLess($lessInput){
        $l = explode("|", $lessInput);
        if(isset($l[0]) && isset($l[1]))
            return " WHERE ".$l[0]." <= ".$l[1];
        else
            return "";
    }


    //Returns the Precedence order of the supplied qualifiers
    private function workOutPrecedence() {
        if(!empty($this->where)) return $this->where;
        if(!empty($this->less)) return $this->less;
        if(!empty($this->greater)) return $this->greater;
        if(!empty($this->average)) return $this->averager;
        if(!empty($this->between)) return $this->between;
        return "";
    }
}