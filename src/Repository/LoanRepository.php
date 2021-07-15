<?php

namespace App\Repository;

use App\Entity\Loan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Loan|null find($id, $lockMode = null, $lockVersion = null)
 * @method Loan|null findOneBy(array $criteria, array $orderBy = null)
 * @method Loan[]    findAll()
 * @method Loan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Loan::class);
    }

    // /**
    //  * @return Loan[] Returns an array of Loan objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Loan
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findByLoan()
    {
        return $this->createQueryBuilder('l')
            ->orderBy('l.loanDate', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByBorrower(string $value)
    {
        return $this->createQueryBuilder('l')
            ->innerJoin('l.borrower', 'k')
            ->andWhere('k.id = :value')
            ->setParameter('value', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByBook(string $value)
    {
        return $this->createQueryBuilder('l')
            ->innerJoin('l.book', 'k')
            ->andWhere('k.id = :value')
            ->setParameter('value', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByReturnDate(string $value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.returnDate < :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByIdAndReturnDate(int $value)
    {
        return $this->createQueryBuilder('l')
            ->where('l.id = :val')
            ->andWhere('l.returnDate IS NULL')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }
}
