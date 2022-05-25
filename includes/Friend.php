<?php

class Friend
{
    public $id;
    public $accepted;
    public $date;

    function __construct($id, $accepted, $date)
    {
        $this->id = $id;
        $this->accepted = $accepted;
        $this->date = $date;
    }
}


?>