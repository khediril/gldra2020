<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Fournisseur;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\FournisseurType;
/**
     * @Route("/fournisseur",)
     */
class FournisseurController extends AbstractController
{
    /**
     * @Route("/", name="fournisseur")
     */
    public function index()
    {
        return $this->render('fournisseur/index.html.twig', [
            'controller_name' => 'FournisseurController',
        ]);
    }
     /**
     * @Route("/add", name="fournisseur_add")
     */
    public function add(Request $request)
    {
        $fournisseur=new Fournisseur();

        $form = $this->createForm(FournisseurType::class,$fournisseur);
            
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // $form->getData() holds the submitted values
                // but, the original `$task` variable has also been updated
                //$task = $form->getData();
        
                // ... perform some action, such as saving the task to the database
                // for example, if Task is a Doctrine entity, save it!
                 $entityManager = $this->getDoctrine()->getManager();
                 $entityManager->persist($fournisseur);
                 $entityManager->flush();
        
                return $this->redirectToRoute('fournisseur_list1');
            }
       
        return $this->render('fournisseur/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
     /**
     * @Route("/list", name="fournisseur_list1")
     */
    public function list()
    {
        $fournisseurs=$this->getDoctrine()->getRepository(Fournisseur::class)->findAll();
      
        
       
        return $this->render('fournisseur/list.html.twig', [
            'fournisseurs' => $fournisseurs,
        ]);
    }
}
