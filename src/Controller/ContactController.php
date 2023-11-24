<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{

    public function __construct(private RequestStack $requestStack, private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/contact', name: 'contact.index')]
    public function index(): Response
    {
       $entity = new Contact();
       $type = ContactType::class;

       $form = $this->createForm($type , $entity);
        $form->handleRequest($this->requestStack->getMainRequest());
    //    dd($entity , $type);
       if($form->isSubmitted() && $form->isValid()){
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

         // messafe de confirmation
         $message = 'Message submited';

         $this->addFlash('notice',$message);

         return $this->redirectToRoute('homepage.index');
       }

       return $this->render('contact/index.html.twig',[
        'form' => $form->createView(),
    ]);
    }
   
}
