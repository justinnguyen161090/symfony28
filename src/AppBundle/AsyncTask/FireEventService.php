<?php

namespace AppBundle\AsyncTask;

use Enqueue\Client\ProducerInterface;

class FireEventService
{
    private $producer;

    public function __construct(ProducerInterface $producer)
    {
        $this->producer = $producer;
    }

    public function fireEvent($data)
    {
        $this->producer->sendCommand("process_data_command", [
            'data' => $data
        ]);
    }
    
}