<?php
namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /*
     *     - parametre promu
     *     requête HTTP:
     *       - contenue dans une classe RequestStack
     *       - injection de dépendances : accéder à une classe dans une autre classe
     *       - dans symfony, l'injection de dépendances se fait par le constructeur
    */
  
     public function __construct(private RequestStack $requestStack){

     }

    #[Route('/',name: 'homepage.index')]
    public function index():Response{

        /**
         * débogoge : 
         *      dump: afficher la donnees dans la page
         *      dd ( dump and die) : afficher la donnee puis stoppper le script
         *      getMainRequest : requete HTTP executee par le PHP
         *      proporiete de la requete 
         *        - request : $_POST
         *        - query : $_GET
         */
        // recuperation d'une donnee envoyé en $_POST
        //$post = $this->requestStack->getMainRequest()->request->get('key');
        // dd($this->requestStack->getMainRequest());
        //dd($post);
        // return new Response('<h1>hellooo</h1>',400);
        // return new Response('{ "key" : "value" }',
        // Response::HTTP_ACCEPTED,[
        //     'Content-Type' => 'application/json'
        // ]);

        // render : appel d'une vue twig , il se point directement vers template 
        // la clé du tablau asoociatf deveint une variable dans twig
        return $this->render('homepage/index.html.twig',[
            'my_array' =>['val0','val1','val2','val3'],
            'assoc_array' =>[
                'key0' => 'val0',
                'key1' => 'val1',
                'key2' => 'val2',
            ],
            'now' => new \DateTime(),
        ]);
    }

    #[Route('/hello/{name}' , name : 'homepage.hello')]

    public function hello ( string $name ):Response {
        return $this->render('homepage/hello.html.twig' , 
        [
            'name' => $name,
        ]);
    }
}