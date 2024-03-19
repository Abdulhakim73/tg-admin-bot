<?php
class DB
{
    function __construct()
    {
        $this->connect = new mysqli("localhost", USERNAME, PASSWORD, DATABASE,);
    }

    private $connect = null;

    /*******database mysqli query********/
    public function query($sql)
    {
        $result = $this->connect->query($sql);
        return $result;
    }

    /*******database multi query********/
    public function mquery($sql)
    {
        $result = $this->connect->multi_query($sql);
        return $result;
    }


}
