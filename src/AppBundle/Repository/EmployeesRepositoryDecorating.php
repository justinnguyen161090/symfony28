<?php

namespace AppBundle\Repository;

use AppBundle\Event\EmployeesSaveEvent;
use AppBundle\Event\EmployeesSearchEvent;
use AppBundle\Repository\EmployeesRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EmployeesRepositoryDecorating
{
    private $repository;
    private $dispatcher;

    public function __construct(EmployeesRepository $repository, EventDispatcherInterface $dispatcher)
    {
        $this->repository = $repository;
        $this->dispatcher = $dispatcher;
    }

    public function saveEmployee($employee)
    {
        $this->dispatcher->dispatch(EmployeesSaveEvent::SAVE, new EmployeesSaveEvent($employee));
    }

    public function findEmployees($request)
    {
        $pagination = $this->dispatcher->dispatch(EmployeesSearchEvent::SEARCH, new EmployeesSearchEvent($this->repository, $request))->getPagination();

        return $pagination;
    }

    public function __call($method, $args)
    {
        return \call_user_func_array(array($this->repository, $method), $args);
    }
}
