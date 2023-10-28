<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\FormbookType;
use App\Form\SearchbookType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use PharIo\Manifest\Manifest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }


    #[Route('/showbook', name: 'app_showbook')]
    public function showbook(BookRepository $bookRepository, Request $req): Response
    {
        $book=$bookRepository->bookbyauthor();
        $sum=$bookRepository->sommebookbycategory();
        $bookdate=$bookRepository->bookbydate();
        $form= $this->createForm(SearchbookType::class);
        $form->handleRequest($req);
        
        if($form->isSubmitted()){
            $Datainput= (int) $form->get('id')->getData();
            $books=$bookRepository->searchbyref($Datainput);

            return $this->renderForm('book/showbook.html.twig', [
                'books' => $books,
                'f'=> $form,
               
            ]);
        }
        return $this->renderForm('book/showbook.html.twig', [
            'books' => $book,
            'f'=> $form,
            'somme' => $sum,
            'book' => $bookdate
        ]);
    }

    
    #[Route('/updatecategory', name: 'app_updatecategory')]
    public function updatecategory(BookRepository $bookRepository): Response
    {
        $book=$bookRepository->updatecategory();
        $book=$bookRepository->findAll();
        return $this->render('book/showbyyears.html.twig', [
            'books' => $book,
        ]);
    }


    /**#[Route('/showbyyears', name: 'app_showbyyears')]
    public function showbyyears(BookRepository $bookRepository, Request $req): Response
    {
        $book=$bookRepository->bookbyyears();
        $form= $this->createForm(SearchbookType::class);
        return $this->renderForm('book/showbyyears.html.twig', [
            'books' => $book,
            'f'=> $form
        ]);
    }**/

    #[Route('/addbook', name: 'app_addbook')]
    public function addbook(ManagerRegistry $managerRegistry, Request $req ): Response
    { $em=$managerRegistry->getManager();
        $book= new Book();
       $form = $this->createForm(FormbookType::class, $book);
       $form->handleRequest($req);

           if($form->isSubmitted() and $form->isValid()){
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('app_showbook');
              }
             
   
        return $this->renderForm('book/addformbook.html.twig', [
            'f' => $form,
           ]);  
    }


    #[Route('/editbook/{id}', name: 'app_editbook')]
    public function editbook($id,ManagerRegistry $managerRegistry ,
     BookRepository $bookRepository , Request $req): Response
    {    $em=$managerRegistry->getManager();
        $dataid=$bookRepository->find($id);
        $form=$this->createForm(FormbookType::class, $dataid);
        $form->handleRequest($req);

        if($form->isSubmitted() and $form->isValid())
        {$em->persist($dataid);
        $em->flush();
        return $this->redirectToRoute('app_showbook');
    
        }
        

        return $this->renderForm('book/editformbook.html.twig', [
            'fe' => $form ,
        ]);
    }
}
