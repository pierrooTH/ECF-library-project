<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Borrower;
use App\Entity\Loan;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('loanDate')
            ->add('returnDate')
            ->add('book', EntityType::class, [
                'class' => Book::class,
                   'choice_label' => function(Book $book) {
                       return "{$book->getTitle()}";
                   },
                   'query_builder' => function (EntityRepository $er) {
                       return $er->createQueryBuilder('l')
                       ->orderBy('l.title', 'ASC')
                       ;
                   },
          ])
            ->add('borrower', EntityType::class, [
            'class' => Borrower::class,
               'choice_label' => function(Borrower $borrower) {
                   return "{$borrower->getFirstname()} {$borrower->getLastname()}";
               },
               'query_builder' => function (EntityRepository $er) {
                   return $er->createQueryBuilder('l')
                   ->orderBy('l.firstname', 'ASC')
                   ->orderBy('l.lastname', 'ASC')
                   ;
               },
      ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Loan::class,
        ]);
    }
}
