<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Departments;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Department controller.
 *
 * @Route("departments")
 */
class DepartmentsController extends Controller
{
    /**
     * Lists all department entities.
     *
     * @Route("/", name="departments_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $departments = $em->getRepository('AppBundle:Departments')->findAll();

        // $serializer = $this->get('serializer');
        // $test = $serializer->denormalize(
        //     $this->get('employees_repository')->getAllDepartments(),
        //     'AppBundle\Entity\Departments[]',
        //     null
        // );

        // var_dump($test);

        return $this->render('departments/index.html.twig', array(
            'departments' => $departments,
        ));
    }

    /**
     * Creates a new department entity.
     *
     * @Route("/new", name="departments_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $department = new Department();
        $form = $this->createForm('AppBundle\Form\DepartmentsType', $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($department);
            $em->flush();

            return $this->redirectToRoute('departments_show', array('id' => $department->getId()));
        }

        return $this->render('departments/new.html.twig', array(
            'department' => $department,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a department entity.
     *
     * @Route("/{id}", name="departments_show")
     * @Method("GET")
     */
    public function showAction(Departments $department)
    {
        $deleteForm = $this->createDeleteForm($department);

        return $this->render('departments/show.html.twig', array(
            'department' => $department,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing department entity.
     *
     * @Route("/{id}/edit", name="departments_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Departments $department)
    {
        $deleteForm = $this->createDeleteForm($department);
        $editForm = $this->createForm('AppBundle\Form\DepartmentsType', $department);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('departments_edit', array('id' => $department->getId()));
        }

        return $this->render('departments/edit.html.twig', array(
            'department' => $department,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a department entity.
     *
     * @Route("/{id}", name="departments_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Departments $department)
    {
        $form = $this->createDeleteForm($department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($department);
            $em->flush();
        }

        return $this->redirectToRoute('departments_index');
    }

    /**
     * Creates a form to delete a department entity.
     *
     * @param Departments $department The department entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Departments $department)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('departments_delete', array('id' => $department->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
