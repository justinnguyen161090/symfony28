<?php

namespace AppBundle\Repository;


class MailerService
{
	private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

	public function onSendMail()
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('TEST')
            ->setFrom('from@kbsoft.co.jp')
            ->setTo('to@kbsoft.co.jp')
            ->setBody('data changed!!!');

        $this->mailer->send($message);
    }
}
