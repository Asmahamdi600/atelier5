<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\FormNameType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Console\Helper\Dumper;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class AuthorController extends AbstractController
{
    public $authors = array(
        array('id' => 1, 'picture' => '/image/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100 ),
        array('id' => 2, 'picture' => '/image/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>  ' william.shakespeare@gmail.com', 'nb_books' => 200),
        array('id' => 3, 'picture' => '/image/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300 ),
        );
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }


    #[Route('/showbyidauthor/{id}', name: 'app_showbyidauthor')]
    public function showbyidauthor($id , AuthorRepository $authorRepository): Response
    {
        $author=$authorRepository->showbyidauthor($id);
    
        return $this->render('author/showbyidauthor.html.twig', [
            'author' => $author,
        ]);
    }       


    #[Route('/showbyemail', name: 'app_showbyemail')]
    public function showbyemail(AuthorRepository $authorRepository): Response
    {
        $author=$authorRepository->triAuthor();
        
        
        
        return $this->render('author/showbauthor.html.twig', [
            'author' => $author,
          
        ]);
    }





    #[Route('/showbauthor', name: 'app_showbauthor')]
    public function showbauthor(AuthorRepository $authorRepository): Response
    {
        $author=$authorRepository->findAll();
        
        
        return $this->render('author/showbauthor.html.twig', [
            'author' => $author,
        ]);
    }

    #[Route('/addauthor', name: 'addauthor')]
    public function addauthor(ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        $author=new Author();

        $author->setUsername("asma");

        $author->setEmail("asma@gmail.com");

        $author->setNbBooks(3);
        $em->persist($author);
        $em->flush();
        return new Response("great add");
    }



    #[Route('/addAuthorForm', name: 'app_addAuthorForm')]
    public function addAuthorForm(ManagerRegistry $managerRegistry , HttpFoundationRequest $req): Response
    {
        $em=$managerRegistry->getManager();

        $author = new Author();
       $form=$this->createForm(FormNameType::class , $author);
       $form->handleRequest($req);
       if ($form->isSubmitted() and $form->isValid()){
        $em->persist($author);
        $em->flush();
        return $this->redirectToRoute('app_showbauthor');
       }
        
        return $this->renderForm('author/addAuthorForm.html.twig', [
            'F' => $form,
        ]);
    }


    #[Route('/editauthor/{id}', name: 'app_editauthor')]
    public function editauthor($id , AuthorRepository $authorRepository ,
     ManagerRegistry $managerRegistry , HttpFoundationRequest $req)  : Response
    {
        #var_dump($id) . die();
        $em=$managerRegistry->getManager();
        $dataid=$authorRepository->find($id);
        #var_dump($dataid) . die();
        $form=$this->createForm(FormNameType::class,$dataid);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($dataid);
            $em->flush();
            return $this->redirectToRoute('app_showbauthor');
        }

        return $this->renderForm('author/editauthor.html.twig', [
            'f' => $form,
        ]);
    }
   
   
    #[Route('/showAuthor/{name}', name: 'app_showAuthor')]
   
    public function showAuthor($name): Response
    { 
        return $this->render('author/showAuthor.html.twig', [
            'namehtml' => $name
        ]);
    }

    #[Route('/list', name: 'app_list')]
    public function list(): Response
    {
       
        return $this->render('author/list.html.twig', [

            'author' => $this->authors
        ]);
    }


    #[Route('/auhtorDetails/{id}', name: 'app_auhtorDetails')]
    public function auhtorDetails($id): Response
    {
        $author = null;

        foreach ($this->authors as $i) {
            if ($i['id'] == $id) {
                $author = $i;
            }
        }
        #var_dump($author) . die();
        return $this->render('author/show.html.twig', [
            'author' => $author,
        ]);
    }

    

    #[Route('/deleteauthor/{id}', name: 'app_deleteauthor')]
    public function deleteauthor($id , ManagerRegistry $managerRegistry , 
    AuthorRepository $authorRepository ,HttpFoundationRequest $req ): Response
    {   $em=$managerRegistry->getManager();
        $id=$authorRepository->find($id);
        $em->remove($id);
        $em->flush();
        
        return $this->redirectToRoute('app_showbauthor');
    }


    
}
