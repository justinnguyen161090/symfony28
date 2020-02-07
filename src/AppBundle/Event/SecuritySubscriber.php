<?php

namespace AppBundle\Event;

use AppBundle\Entity\MUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SecuritySubscriber implements EventSubscriberInterface
{
    private $em;
    private $session;

    public function __construct(EntityManagerInterface $em, SessionInterface $session)
    {
        $this->em = $em;
        $this->session = $session;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AuthenticationEvents::AUTHENTICATION_SUCCESS => 'onAuthSuccess',
        ];
    }

    public function onAuthSuccess(AuthenticationEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        if ($user instanceof MUser)
        {
            // die('xxx');
            // $this->session->set('error_login_custom', $event->getUri());
            // $user->setLoginDate(new \DateTime());

            // $this->em->persist($user);
            // $this->em->flush();
        }
    }
}
