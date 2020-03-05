<?php

namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

//use Symfony\Component\HttpFoundation\Response;


class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index()
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
     /**
     * @Route("/test2", name="test2")
     */
    public function index2() : Response
    {
       
       return new Response("<html><head></head><body><h1>bonjour</h1></body></html>");
        /* return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);*/
    }
     /**
     * @Route("/test3", name="test3")
     */
    public function index3() : Response
    {
        $tab=["test1","test2","test3","test4",];
       //return new Response("<html><head></head><body><h1>bonjour</h1></body></html>");
        return $this->render('test/index3.html.twig', [
            'names'=>$tab
            
        ]);
    }
    /**
     * @Route("/somme/{n1}/{n2}", name="somme")
     */
    public function somme($n1,$n2) : Response
    {
        $somme = $n1 + $n2;
       
            return $this->render('test/somme.html.twig', [
            'n1'=>$n1,'n2'=>$n2,'somme'=>$somme
            
        ]);
    }
}
