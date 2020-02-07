<?php

namespace AppBundle\Event;

use AppBundle\Event\EmployeesSaveEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Repository\MailerService;
use AppBundle\AsyncTask\FireEventService;

class EmployeesSaveEventSubscriber implements EventSubscriberInterface
{
    private $em;
    private $mailer;
    private $producer;

    public function __construct(EntityManagerInterface $em, MailerService $mailer, FireEventService $producer)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->producer = $producer;
    }

    public static function getSubscribedEvents()
    {
        return [
            EmployeesSaveEvent::SAVE => 'onSave'//2 prioty flush + send mail???
        ];
    }

    public function onSave($event)
    {
        $flag = false;
        $employee = $event->getEmployee();

        if(null === $employee->getId()) $flag= true;

        else $flag = $this->isChangeData();

        //if changed flush + clear cache result + send mail notice changed
        if($flag)
        {
            $this->em->persist($employee);
            $this->em->flush();

            $this->em->getConfiguration()->getResultCacheImpl()->delete('employees_id');

            // $this->mailer->onSendMail();

            $this->producer->fireEvent('Hello world');
        }
    }

    //check change entity
    public function isChangeData()
    {
        $uow = $this->em->getUnitOfWork();
        $uow->computeChangeSets();

        $scheduledEntityChanges = array_merge(
            // $uow->getScheduledEntityInsertions(),
            $uow->getScheduledEntityUpdates(),
            // $uow->getScheduledEntityDeletions(),
            // $uow->getScheduledCollectionUpdates(),
            $uow->getScheduledCollectionDeletions()
        );

        foreach ($scheduledEntityChanges as $entity)
        {
            $changeset = $uow->getEntityChangeSet($entity);

            if (is_array($changeset)) return true;
        }

        return false;
    }
}
