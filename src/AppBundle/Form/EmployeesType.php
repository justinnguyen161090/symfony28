<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Repository\EmployeesRepository;
use AppBundle\Entity\Departments;

class EmployeesType extends AbstractType
{
    private $emp;

    public function __construct(EmployeesRepository $emp)
    {
        $this->emp = $emp;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('birthDate')
            ->add('firstName')
            ->add('lastName')
            ->add('gender')
            ->add('hireDate')
            ->add('departments', EntityType::class, array(
                'class'        => Departments::class,
                'choice_label' => 'deptName',
                'multiple'     => true,
                'expanded'     => true,
                'choices'      => $this->emp->findAllDepartments(),
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Employees'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'employees';
    }


}
