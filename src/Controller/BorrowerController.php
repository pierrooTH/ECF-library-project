<?php

namespace App\Controller;

use App\Entity\Borrower;
use App\Entity\User;
use App\Form\BorrowerType;
use App\Repository\BorrowerRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface; 
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * @Route("/borrower")
 */
class BorrowerController extends AbstractController
{
    /**
     * @Route("/", name="borrower_index", methods={"GET"})
     */
    public function index(BorrowerRepository $borrowerRepository, Request $request, PaginatorInterface $paginator): Response
    {
        // return $this->render('borrower/index.html.twig', [
        //     'borrowers' => $borrowerRepository->findAll(),
        // ]);

        $donnees = $this->getDoctrine()->getRepository(Borrower::class)->findBy([],['id' => 'ASC']);

        $borrower = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            15 // Nombre de résultats par page
        );
        
        return $this->render('borrower/index.html.twig', [
            'borrowers' => $borrower,
        ]);
    }

    /**
     * @Route("/new", name="borrower_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $borrower = new Borrower();
        $form = $this->createForm(BorrowerType::class, $borrower);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $borrower->getUser();
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('user')->get('plainPassword')->getData()
                )
            );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($borrower);
            $entityManager->flush();

            return $this->redirectToRoute('borrower_index');
        }

        return $this->render('borrower/new.html.twig', [
            'borrower' => $borrower,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="borrower_show", methods={"GET"})
     */
    public function show(Borrower $borrower): Response
    {
        return $this->render('borrower/show.html.twig', [
            'borrower' => $borrower,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="borrower_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Borrower $borrower): Response
    {
        $form = $this->createForm(BorrowerType::class, $borrower);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('borrower_index');
        }

        return $this->render('borrower/edit.html.twig', [
            'borrower' => $borrower,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="borrower_delete", methods={"POST"})
     */
    public function delete(Request $request, Borrower $borrower): Response
    {
        if ($this->isCsrfTokenValid('delete'.$borrower->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($borrower);
            $entityManager->flush();
        }

        return $this->redirectToRoute('borrower_index');
    }

}
