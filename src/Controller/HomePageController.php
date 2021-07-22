<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface; 

/**
 * @Route("/")
 */
class HomePageController extends AbstractController
{
    /**
     * @Route("/", name="home_index", methods={"GET", "POST"})
     */
    public function index(BookRepository $bookRepository, Request $request, PaginatorInterface $paginator): Response
    {
            $donnees = $this->getDoctrine()->getRepository(Book::class)->findBy([],['id' => 'ASC']);

            $book = $paginator->paginate(
                $donnees, // Requête contenant les données à paginer (ici nos articles)
                $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                15 // Nombre de résultats par page
            );

            return $this->render('book/index.html.twig', [
                'books' => $book,
            ]);
    }

   

}
