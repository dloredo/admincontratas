<?php
namespace App\Http;

class RequestResponse 
{
    static $ESTATUS_OK = 1;
    static $ESTATUS_DATA_NOT_FOUND = 2;
    static $ESTATUS_BD_ERROR = 3;
    static $ESTATUS_UNEXPECTED_ERROR = 3;

    public $data;
    public $message;
    public $estatus;

    function __construct($data = null,$message = null,$estatus = null)
    {
        $this->data = $data;
        $this->message = $message;
        $this->estatus = $estatus;

    }
}