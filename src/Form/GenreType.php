<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Book;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('books', EntityType::class, [
                'class' => Book::class,
                'choice_label' => function(Book $book) {
                return "{$book->getTitle()}";
                },
                'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('g')
                ->orderBy('g.title', 'ASC');
                },
                'multiple' => true,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Genre::class,
        ]);
    }
}
