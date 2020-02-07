<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Repository\EmployeesRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EmployeesSearchType extends AbstractType
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
            ->setMethod('GET')
            ->add('birthDate', DateType::class, array(
                'widget'   => 'single_text',
                'required' => false,
            ))
            ->add('firstName', TextType::class, array(
                'required' => false,
            ))
            ->add('lastName', TextType::class, array(
                'required' => false,
            ))
            ->add('gender', ChoiceType::class, array(
                'multiple'    => false,
                'expanded'    => false,
                'required'    => false,
                'empty_value' => '',
                'choices'     => array(
                    '0' => 'FeMale',
                    '1' => 'Male'
                ),
            ))
            ->add('hireDate', DateType::class, array(
                'widget'   => 'single_text',
                'required' => false,
            ))
            ->add('departments', ChoiceType::class, array(
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'choices'  => array_column($this->emp->getAllDepartments(), 'deptName', 'id'),
            ))
            ->add('refresh', SubmitType::class, array(
                'label' => 'Refresh'
            ))
            ->add('search', SubmitType::class, array(
                'label' => 'Search'
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            // 'data_class' => 'AppBundle\Entity\Employees'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'employees_search';
    }


}
