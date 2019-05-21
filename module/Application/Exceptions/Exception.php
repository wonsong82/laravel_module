<?php

namespace Module\Application\Exceptions;



class Exception extends \Exception
{
    public $messages;


    /***
     * Exception constructor.
     * @param object|array|string $messages
     */
    public function __construct($messages)
    {
        parent::__construct('Application encountered error.');

        $this->messages = $messages;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }





}
