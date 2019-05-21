<?php

namespace Module\Application\Exceptions;

class ValidationException extends Exception
{
    public $messages = [];


    /***
     * ValidationException constructor.
     * @param string|array|object $messages
     */
    public function __construct($messages)
    {
        parent::__construct('Validation failed.');

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
