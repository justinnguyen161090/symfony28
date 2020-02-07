<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Knp\Component\Pager\PaginatorInterface;
use AppBundle\Event\EmployeesSearchEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class EmployeesSearchEventSubscriber implements EventSubscriberInterface
{
    private $paginator;
    private $session;

    public function __construct(PaginatorInterface $paginator, SessionInterface $session)
    {
        $this->paginator = $paginator;
        $this->session   = $session;
    }

    public static function getSubscribedEvents()
    {
        return [
            EmployeesSearchEvent::SEARCH => [
                ['onSetUri', 0],
                ['onPagination', 1],
            ]
        ];
    }

    public function onPagination($event)
    {
        $query = $event->getQuery();
        $page  = $event->getPage();

        $pagination = $this->paginator->paginate(
            $query,
            $page,
            5
        );

        $event->setPagination($pagination);
    }

    public function onSetUri($event)
    {
        $this->session->set('uri_employees_index', $event->getUri());
    }
}
