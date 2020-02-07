<?php

namespace AppBundle\AsyncTask;

use AppBundle\Repository\MailerService;
use Enqueue\Client\CommandSubscriberInterface;
use Enqueue\Util\JSON;
use Interop\Queue\PsrContext;
use Interop\Queue\PsrMessage;
use Interop\Queue\PsrProcessor;

class EventProcessor implements PsrProcessor, CommandSubscriberInterface
{
    private $mailer;

    public function __construct(MailerService $mailer)
    {
        $this->mailer = $mailer;
    }

    public function process(PsrMessage $message, PsrContext $context)
    {
        $decodedMessage = JSON::decode($message->getBody());
        $data = $decodedMessage['data'];

        $this->mailer->onSendMail();

        return self::ACK;
    }
    
    public static function getSubscribedCommand()
    {
        return ['process_data_command'];
    }
}