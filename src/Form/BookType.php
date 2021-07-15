<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('editionYears')
            ->add('pagesNumber')
            ->add('codeIsbn')
            ->add('author')
            // Déclaration d'un champ EntityType
            ->add('author', EntityType::class, [
                  'class' => Author::class,
                     'choice_label' => function(Author $author) {
                         return "{$author->getLastname()}";
                     },
                     'query_builder' => function (EntityRepository $er) {
                         return $er->createQueryBuilder('s')
                             ->orderBy('s.lastname', 'ASC')
                         ;
                     },
            ])
            //->add('genres')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
