<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;

class EmployeesRepository
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function saveEmployee($employee)
    {
        $this->em->persist($employee);
        $this->em->flush();
    }

    public function deleteEmployee($employee)
    {
        $this->em->remove($employee);
        $this->em->flush();
    }

	public function findEmployees($params)
    {
        $query = $this->em->createQueryBuilder()
            ->select('a, au')
            ->from('AppBundle:Employees', 'a')
            ->leftjoin('a.departments', 'au')
            ->where(' 1 = 1 ');

        if(isset($params['employees_search']['gender']) && $params['employees_search']['gender'] != '')
        {
            $query->andWhere('a.gender = '.$params['employees_search']['gender']);
        }

        return $query->getQuery()
                    ->useResultCache(true, null, 'employees_id');//???container
    }

    public function getAllDepartments()
    {
        $qb = $this->em->createQueryBuilder()
            ->select('a.id, a.deptName')
            ->from('AppBundle:Departments', 'a');

        return $qb->getQuery()
            ->useResultCache(true, null, 'departments_id')
            ->getResult();
    }

    public function findAllDepartments()
    {
        return $this->em->getRepository('AppBundle:Departments')->findBy(
            [],
            ['deptName' => 'DESC']
        );
    }

}
