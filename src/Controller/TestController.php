<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Book;
use App\Entity\Loan;
use App\Repository\UserRepository;
use App\Repository\BookRepository;
use App\Repository\LoanRepository;
use App\Repository\GenreRepository;
use App\Repository\BorrowerRepository;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index(
        UserRepository $userRepository,
        BorrowerRepository $borrowerRepository,
        BookRepository $bookRepository,
        AuthorRepository $authorRepository,
        GenreRepository $genreRepository, 
        LoanRepository $loanRepository
        ): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $userRepository->findAll();
        dump($user);

        $user = $userRepository->findOneBy(['email'=>'foo.foo@example.com']);
        dump($user);

        $user = $userRepository->find(1);
        dump($user);

        // - la liste complète de tous les livres
        $book = $bookRepository->findAll();
        dump($book);
        // - les données du livre dont l'id est `1`
        $book = $bookRepository->find(1);
        dump($book);
        // - la liste des livres dont le titre contient le mot clé `lorem`
        $book = $bookRepository->findByTitle('lorem');
        dump($book);
        // - la liste des livres dont l'id de l'auteur est `2`
        $book = $bookRepository->findByAuthor(2);
        dump($book);

        // - la liste des livres dont le genre contient le mot clé `roman`
        $books = $bookRepository->findByGenres('roman');
        dump($books);

        $borrowerRole = $userRepository->findByRole('ROLE_BORROWER');
        dump($borrowerRole);

        $authors = $authorRepository->findAll();
        $genres = $genreRepository->findAll();

        // ajouter un nouveau livre 
        $book = new Book();
        $book->setTitle('Totum autem id externum');
        $book->setEditionYears('2020');
        $book->setPagesNumber('300');
        $book->setCodeIsbn('9790412882714');
        $book->setAuthor($authors[1]);
        $book->addGenre($genres[5]);
        $entityManager->persist($book);
        
        $entityManager->flush();
        dump($book);

        $bookId2 = $bookRepository->findAll()[1];
        $bookId2->setTitle('Aperiendum est igitur');
        $bookId2->addGenre($genres[4]);
        $entityManager->persist($bookId2);
        $entityManager->flush();
        dump($bookId2);

        // supprimer le livre qui a pour ID 123 : 
        // $removeBook = $bookRepository->findById(123)[0];
        // $entityManager->remove($removeBook);
        // $entityManager->flush();
        //dump($removeBook);

        // Les emprunteurs 

        // recherche de tous les emprunters 
        $borrower = $borrowerRepository->findAll();
        dump($borrower);

        // recherche de l'emprunteur qui a pour ID : 3
        $borrower = $borrowerRepository->find(3);
        dump($borrower);

        // emprunteur qui est relié au user dont l'id est `3`
        $borrower = $borrowerRepository->findOneByUser(3);
        dump($borrower);

        // emprunteurs dont le nom ou le prénom contient le mot clé `foo`
        $borrower = $borrowerRepository->findByFirstnameOrLastname('foo');
        dump($borrower);

        // emprunteurs dont le téléphone contient le mot clé `1234`
        $borrower = $borrowerRepository->findByPhoneNumber('1234');
        dump($borrower);

        // emprunteurs dont la date de création est antérieure au 01/03/2021 exclu
        $borrower = $borrowerRepository->findOneByDate('2021-03-01');
        dump($borrower);

        // emprunteurs inactifs
        $borrowerInactif = $borrowerRepository->findByActive(false);
        dump($borrowerInactif);

        // emprunts 
        // la liste des 10 derniers emprunts au niveau chronologique :
        $loans = $loanRepository->findByLoan();
        dump($loans);

        // emprunts de l'emprunteur dont l'id est `2`
        $borrowerId2 = $loanRepository->findByBorrower(2);
        dump($borrowerId2);

        // emprunts du livre dont l'id est 3
        $bookId3 = $loanRepository->findByBook(3);
        dump($bookId3);

        // emprunts qui ont été retournés avant le 01/01/2021
        $loan = $loanRepository->findByReturnDate('2021-01-01');
        dump($loan);

        // emprunts qui n'ont pas encore été retournés
        // $NotReturnLoan = $loanRepository->findByReturnDate();
        // dump($loan);

        // emprunt du livre dont l'id est `3` et qui n'a pas encore été retournés
        $loan = $loanRepository->findByIdAndReturnDate(3);
        dump($loan);

        // création emprunts 
        
        // récupération de tous les emprunteurs et livres
        $borrowers = $borrowerRepository->findAll();
        $books = $bookRepository->findAll();

        $loan = new Loan();
        $loan->setLoanDate(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-12-01 16:00:00'));
        $loan->setBorrower($borrowers[0]);
        $loan->setBook($books[0]);
        $entityManager->persist($loan);
        $entityManager->flush();
        dump($loan);

        // mise à jour emprunt
        $loan = $loanRepository->findOneById(3);
        $loan->setReturnDate(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-05-01 10:00:00'));
        $entityManager->persist($loan);
        $entityManager->flush();
        dump($loan);

        // supprimer l'emprunt dont l'id est `42`
        // $loan = $loanRepository->findOneById(3);
        // $entityManager->remove($loan);
        // $entityManager->flush();
        // dump($loan);
        
        exit();

    }           
}
