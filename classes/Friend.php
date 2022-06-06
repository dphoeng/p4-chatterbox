<?php

class Friend
{
    public $id;
    public $request_type;
    public $date;

    function __construct($id, $request_type, $date)
    {
        $this->id = $id;
        $this->request_type = $request_type;
        $this->date = $date;
    }
}


?>