<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AutherType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AutherController extends AbstractController
{

    public $authers = array(
        array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
        array('id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>  ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
        array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
    );
    #[Route('/auther', name: 'app_auther')]
    public function index(): Response
    {
        return $this->render('auther/index.html.twig', [
            'controller_name' => 'AutherController',
        ]);
    }




    #[Route('/addauthor', name: 'addauthor')]
    public function addauthor(ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        $author = new Author();
        $author->setUsername("3a55");
        $author->setEmail("3a56@esprit.tn");
        $em->persist($author);
        $em->flush();

        return new Response("great add");
    }




    #[Route('/addformauther', name: 'addformauther')]
    public function addformauther(ManagerRegistry $managerRegistry, Request $req): Response
    {
        $em = $managerRegistry->getManager();
        $author = new Author();
        $form = $this->createForm(AutherType::class, $author);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($author);
            $em->flush();
            return $this->redirect('showdbauther');
        }
        return $this->renderForm('auther/addformauther.html.twig', [
            'f' => $form
        ]);
    }




    #[Route('/editauther/{id}', name: 'editauther')]
    public function editauther($id, AuthorRepository $authorRepository, ManagerRegistry $managerRegistry, Request $req): Response
    {
         //var_dump($id) . die();
        $em = $managerRegistry->getManager();
        $dataid = $authorRepository->find($id);
        //var_dump($dataid) . die();
        $form = $this->createForm(AutherType::class, $dataid);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($dataid);
            $em->flush();
            return $this->redirectToRoute('showdbauther');
        }

        return $this->renderForm('auther/editauther.html.twig', [
            'form' => $form
        ]);
    }




    #[Route('/deletauther/{id}', name: 'deletauther')]
    public function deletauther($id, ManagerRegistry $managerRegistry, AuthorRepository $repo): Response
    {
        $em = $managerRegistry->getManager();
        $id = $repo->find($id);
        $em->remove($id);
        $em->flush();
        return $this->redirectToRoute('showdbauther');
    }




    #[Route('/showAuther', name: 'app_showauther')]
    public function showauther($name): Response
    {
        return $this->render('auther/show.html.twig', [
            'name' => $name ,
        ]);
    }


    #[Route('/showtableauther', name: 'showtableauther')]
    public function showtableauther(): Response
    {
     
            
        return $this->render('auther/table.html.twig', [
            'auther' => $this->authers,
        ]);
    }



    #[Route('/showbyidauther/{id}', name: 'showbyidauther')]
    public function showbyidauther($id): Response
    {
       //  var_dump($id).die();
        $auther=null;
        foreach($this->authers as $autherD)
        {
            if($autherD ['id']==$id){
                $auther=$autherD;

            }
        
        }
        //var_dump($auther) . die();
       

        return $this->render('auther/showbyid.html.twig', [
            'auther' => $auther,
        ]);
    }
    



    #[Route('/showdbauther', name: 'showdbauther')]
    public function showdbauther(AuthorRepository $authorRepository , Request $req): Response
    {

       // $author = $authorRepository->findAll();
        $author=$authorRepository->triauther();
        $form=$this->createForm(MinmaxType::class);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $min=$form->get('min')->getData();
            $max=$form->get('max')->getData();
         $books=$authorRepository->searchbyminmax($min,$max);
        }

        return $this->render('auther/showdbauther.html.twig', [
            'author' => $author
        ]);
    }

}
