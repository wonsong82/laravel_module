<?php

namespace Module\Application\Exceptions;



class NotChangedException extends \Exception
{
    public $messages;


    /***
     * Exception constructor.
     * @param string $message
     * @internal param string $mesasge
     * @internal param array|object|string $messages
     */
    public function __construct($message = '')
    {
        parent::__construct($message);

        $this->messages = [$message];
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
