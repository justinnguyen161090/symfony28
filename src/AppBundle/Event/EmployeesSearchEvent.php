<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class EmployeesSearchEvent extends Event
{
    const SEARCH = 'employees_searched';

    private $query;
    private $request;
    private $pagination;
    private $params;

    public function __construct($repository, $request)
    {
        $this->request = $request;
        $this->params  = $request->query->all();
        $this->query   = $repository->findEmployees($this->params);
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function getPage()
    {
        return $this->params['page']?? 1;
    }

    public function getUri()
    {
        return $this->request->getUri();
    }

    public function setPagination($pagination)
    {
        $this->pagination = $pagination;
    }

    public function getPagination()
    {
        return $this->pagination;
    }
}
