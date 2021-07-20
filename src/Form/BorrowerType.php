<?php

namespace App\Form;

use App\Entity\Borrower;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class BorrowerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', UserType::class, [
                "label_attr" => [
                    'class' => 'd-none'
                ]
            ])
            ->add('lastname')
            ->add('firstname')
            ->add('phoneNumber')
            ->add('active')
            ->add('creationDate')
            //->add('modificationDate')
            // ->add('user', EntityType::class, [
            //     // looks for choices from this entity
            //     'class' => User::class,

            //     // uses the User.username property as the visible option string
            //     'choice_label' => 'username',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Borrower::class,
        ]);
    }
}
