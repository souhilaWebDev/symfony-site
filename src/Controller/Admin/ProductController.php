<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\ByteString;

#[Route('/admin')]
class ProductController extends AbstractController
{
    public function __construct( private ProductRepository $productRepository ,private RequestStack $requestStack, private EntityManagerInterface $entityManager){
    }

    #[Route('/product', name: 'admin.product.index')]
    public function index(): Response
    {
        return $this->render('admin/product/index.html.twig', [
            'products' => $this->productRepository->findAll(),
        ]);
    }

    #[Route('/product/form', name: 'admin.product.form')]
    #[Route('/product/update/{id}', name: 'admin.product.update')]
    public function form(int $id = null): Response{
        
        // creation d un formulaire
        $entity = $id ? $this->productRepository->find($id) : new Product();
        $type = ProductType::class;

        //concerver le nom de l image du produit au as où il n y a pas de selection d'image lors de la modification
        $entity->prevImage = $entity->getImage();

        $form = $this->createForm($type , $entity);

        // récuperer la saisie précedente dans la requete http
        $form->handleRequest($this->requestStack->getMainRequest());

        //si le formulaire et valise et soumis
        if($form->isSubmitted() && $form->isValid()){
            
            //gestion de l'image
            $filename = ByteString::fromRandom(32)->lower();

            // accéder à la classe UploadFile à partir de la propriete image de l'entité
            $file = $entity->getImage();

            // dd($filename , $entity);

            if($file instanceof UploadedFile ){
                //extension du ficher 
                $fileExtension = $file->guessClientExtension();

                // transfert de l image vers public/img
                $file->move('img' , "$filename.$fileExtension");

                //modifier la proporiete imagede l entite
                $entity->setImage("$filename.$fileExtension");

                //supprimer l image precedente
                if($id) unlink("img/{$entity->prevImage}");
            }
            //si une image n a pas ete selectionner pour modifier
            else{
                // rcuperer la valeur de lapropriete preImage
                $entity->setImage($entity->prevImage);
            }
            
            // dd($entity);
            // inserer dans la base
            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            // messafe de confirmation
            $message = $id ? 'Product updat:ed' : 'Product created';

            //message flash : message stocker en session supprimé suite à son affichage
            // notice n'est un texte que nous on choisi
            $this->addFlash('notice',$message);

            //redirection vers la page d'acceuil de l'admin
            return $this->redirectToRoute('admin.product.index');

        }

        return $this->render('admin/product/form.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    #[Route('/product/delete/{id}' , name:'admin.product.delete')]
    public function delete(int $id):RedirectResponse{
        //selectionner l'entite à supprimer
        $entity = $this->productRepository->find($id);

        // supprimer l'entite 

        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        // supprimer l image
        unlink("img/{$entity->getImage()}");

        // message de confirmation
        $this->addFlash('notice' , 'Product deleted');

        //rediction
        return $this->redirectToRoute('admin.product.index');
    }

    
}