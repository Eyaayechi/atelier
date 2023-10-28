<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Form\MinmaxType;
use App\Form\SearchType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
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
    #[Route('/addbook', name: 'addbook')]
    public function addbook(ManagerRegistry $managerRegistry,Request $request): Response
    {
        $em=$managerRegistry->getManager();
        $book=new Book();
        $form=$this-> createForm(BookType::class,$book);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($book);
            $em->flush();
        
    }
    return $this->renderForm('book/addbook.html.twig', [
        'f' => $form
    ]);
}


#[Route('/showbook', name: 'showbook')]
public function showbook(BookRepository $bookRepository, Request $req): Response
{

    $book = $bookRepository->findAll();
    $form=$this->createForm(SearchType::class);
   
    $form->handleRequest($req);
    if($form->isSubmitted()){
      $data=$form->get('ref')->getData();

     

     $books=$bookRepository->searchbyref($data);
     

    return $this->renderForm('book/showbook.html.twig', [
        'book' => $books,
        'f'=>$form
    ]);
    }

    return $this->renderForm('book/showbook.html.twig', [
        'book' => $book,
        'f'=>$form
    ]);
}



#[Route('/editbook/{id}', name: 'editbook')]
    public function editbook($ref, BookRepository $bookRepository, ManagerRegistry $managerRegistry, Request $req): Response
    {
         //var_dump($id) . die();
        $em = $managerRegistry->getManager();
        $dataid = $bookRepository->find($ref);
        //var_dump($dataid) . die();
        $form = $this->createForm(BookType::class, $dataid);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($dataid);
            $em->flush();
            return $this->redirectToRoute('showbook');
        }

        return $this->renderForm('auther/editbook.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/deletebook/{id}', name: 'deletebook')]
    public function deletebook($ref, ManagerRegistry $managerRegistry, BookRepository $repo): Response
    {
        $em = $managerRegistry->getManager();
        $ref = $repo->find($ref);
        $em->remove($ref);
        $em->flush();
        return $this->redirectToRoute('showbook');
    }


}