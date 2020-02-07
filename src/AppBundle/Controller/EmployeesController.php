<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Employees;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Enqueue\Client\Message;
use Enqueue\Client\ProducerInterface;

/**
 * Employee controller.
 *
 * @Route("employees")
 */
class EmployeesController extends Controller
{
    /**
     * Lists all employee entities.
     *
     * @Route("/", name="employees_index")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm('AppBundle\Form\EmployeesSearchType');
        $form->handleRequest($request);

        if($form->get('refresh')->isClicked())
        {
            return $this->redirectToRoute('employees_index');
        }

        $employees = $this->get('employees_repository')->findEmployees($request);

        // dump($employees);

        return $this->render('AppBundle:Employees:index.html.twig', array(
            'employees' => $employees,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new employee entity.
     *
     * @Route("/new", name="employees_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $employee = new Employees();
        $form = $this->createForm('AppBundle\Form\EmployeesType', $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->get('employees_repository')->saveEmployee($employee);

            return $this->redirectToRoute('employees_show', array('id' => $employee->getId()));
        }

        return $this->render('AppBundle:Employees:new.html.twig', array(
            'employee' => $employee,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a employee entity.
     *
     * @Route("/{id}", name="employees_show")
     * @Method("GET")
     */
    public function showAction(Employees $employee)
    {
        $deleteForm = $this->createDeleteForm($employee);

        return $this->render('AppBundle:Employees:show.html.twig', array(
            'employee' => $employee,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing employee entity.
     *
     * @Route("/{id}/edit", name="employees_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Employees $employee)
    {
        $deleteForm = $this->createDeleteForm($employee);
        $editForm = $this->createForm('AppBundle\Form\EmployeesType', $employee);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid())
        {
            $this->get('employees_repository')->saveEmployee($employee);

            // $producer = $this->get('enqueue.producer');

            // $producer->sendEvent('foo_topic', 'Hello world');

            // $producer->sendEvent('bar_topic', ['bar' => 'val']);

            // $message = new Message();
            // $message->setBody('baz');
            // $producer->sendEvent('baz_topic', $message);

            return $this->redirectToRoute('employees_edit', array('id' => $employee->getId()));
        }

        return $this->render('AppBundle:Employees:edit.html.twig', array(
            'employee' => $employee,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a employee entity.
     *
     * @Route("/{id}", name="employees_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Employees $employee)
    {
        $form = $this->createDeleteForm($employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->get('employees_repository')->deleteEmployee($employee);
        }

        return $this->redirectToRoute('employees_index');
    }

    /**
     * Creates a form to delete a employee entity.
     *
     * @param Employees $employee The employee entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Employees $employee)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('employees_delete', array('id' => $employee->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
