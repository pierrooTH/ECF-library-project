<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Genre;
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
            ->add('author', EntityType::class, [
                  'class' => Author::class,
                     'choice_label' => function(Author $author) {
                         return "{$author->getFirstname()} {$author->getLastname()}";
                     },
                     'query_builder' => function (EntityRepository $er) {
                         return $er->createQueryBuilder('a')
                         ->orderBy('a.firstname', 'ASC')
                         ->orderBy('a.lastname', 'ASC')
                         ;
                     },
            ])
            ->add('genres', EntityType::class, [
                'class' => Genre::class,
                'choice_label' => function(Genre $genre) {
                return "{$genre->getName()}";
                },
                'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('b')
                ->orderBy('b.name', 'ASC');
                },
                'multiple' => true,
                'expanded' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
