<?php


namespace App\Form\Type;


use App\Entity\Operation;
use App\Entity\Panel;
use App\Entity\Image;
use App\Entity\Station;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//
            ->add('station', EntityType::class, [
                'class'=> 'App\Entity\Station',
                'placeholder' => 'Select Station',
            ])

            ->add('panel', EntityType::class, [
                'class'=> 'App\Entity\Panel',
                'placeholder' => 'Select Panel',
                ])

            ->add('commentaire', TextType::class, array('attr' => array('class' => 'form-control', 'required' => true)))

            ->add('libelle', TextType::class, array('attr' => array('class' => 'form-control', 'required' => true)))



            ->add('image', FileType::class,[
                'label' => 'Image Before',
                'multiple' => false,
                'mapped' => false,
                'required' => false,
            ])

            ->add('imageAfter', FileType::class,[
                'label' => 'Image After',
                'multiple' => false,
                'mapped' => false,
                'required' => false,
            ]);


//            ->add('Station', EntityType::class, array(
//                // query choices from this entity
//                'required'=>true,
//                'class' => Station::class,
//            ))
//
//            ->add('Panel', EntityType::class, array(
//                // query choices from this entity
//                'required'=>true,
//                'class' => Panel::class,
////                'query_builder'=> function (EntityRepository $repo){
////                    return $repo->createQueryBuilder('f')
////                    ->where('f.id == :id')
////                    ->setParameter('id',0);
////    }
//            ))







//
//            ->add('save', SubmitType::class, array(
//                'label' => 'Save',
//                'attr' => array('class' => 'btn btn-primary mt-3')
//            ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Operation::class,
        ]);

    }

}


//        $builder ->addEventListener(
//            FormEvents::PRE_SET_DATA,
//            function (FormEvent $event) {
//                $form = $event->getForm();
//
//                $data = $event ->getData();
//
//                $station = $data->getStation();
//                $panels = null === $station ? [] : $station->getPanels();
//            )



//        $formModifier = function (FormInterface $form, Station $station = null) {
//            $panels = null === $station ? [] : $station->getPanels();
//
//            $form->add('panel', EntityType::class, [
//                'class' => 'App\Entity\Position',
//                'placeholder' => '',
//                'choices' => $panels,
//            ]);
//        };
//
//        $builder->addEventListener(
//            FormEvents::PRE_SET_DATA,
//            function (FormEvent $event) use ($formModifier) {
//                // this would be your entity, i.e. SportMeetup
//                $data = $event->getData();
//
//                $formModifier($event->getForm(), $data->getStation());
//            }
//        );
//
//        $builder->get('station')->addEventListener(
//            FormEvents::POST_SUBMIT,
//            function (FormEvent $event) use ($formModifier) {
//                // It's important here to fetch $event->getForm()->getData(), as
//                // $event->getData() will get you the client data (that is, the ID)
//                $station = $event->getForm()->getData();
//
//                // since we've added the listener to the child, we'll have to pass on
//                // the parent to the callback functions!
//                $formModifier($event->getForm()->getParent(), $station);
//            }
//        )


//            ->add('dateCreation', DateType::class ,array('required' => true, 'label' => 'Date',))

//            ->add('User', EntityType::class, array(
//                // query choices from this entity
//                'required'=>true,
//                'class' => User::class
//            ))