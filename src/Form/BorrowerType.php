<?php

namespace App\Form;

use App\Entity\Borrower;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BorrowerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname')
            ->add('firstname')
            ->add('phoneNumber')
            ->add('active')
            ->add('creationDate')
            ->add('modificationDate')
            ->add('user', EntityType::class, [
                 'class' => User::class,
                 'choice_label' => function(User $user) {
                 return "{$user->getUser()}";
                 },
                 'query_builder' => function (EntityRepository $er) {
                 return $er->createQueryBuilder('b')
                 ->orderBy('b.user', 'ASC')
                 ;
                 },
                 'multiple' => true,
             ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Borrower::class,
        ]);
    }
}
