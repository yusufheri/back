<?php


namespace App\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("search", SearchType::class, ['label' => false,
                'attr' => ['required' => true, 'placeholder' => 'Search']]);
//            ->add('search', SubmitType::class, array(
//               'label' => 'Save',
//              'attr' => array('class' => 'btn btn-primary mt-3')
//    ));

    }
}