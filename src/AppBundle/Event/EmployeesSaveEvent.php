<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class EmployeesSaveEvent extends Event
{
    const SAVE = 'employees_saved';

    private $employee;

    public function __construct($employee)
    {
        $this->employee = $employee;
    }

    public function getEmployee()
    {
        return $this->employee;
    }
}
