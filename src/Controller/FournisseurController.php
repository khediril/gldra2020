<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Fournisseur;
use App\Form\FournisseurType;
use App\Repository\FournisseurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
     * @Route("/fournisseur",)
     */
class FournisseurController extends AbstractController
{
     
    private $repository;

    public function __construct(FournisseurRepository $repo)
    {
        $this->repository=$repo;
    }
    /**
     * @Route("/edit/{id}", name="fournisseur_edit")
     */
    public function edit(Request $request,$id)
    {
         $fournisseur=$this->repository->find($id);
        

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
            'form' => $form->createView(),'txtbtn'=>'Modifier'
        ]);
    }
    
    
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
            'form' => $form->createView(),'txtbtn'=>'Ajouter'
        ]);
    }
     /**
     * @Route("/list", name="fournisseur_list1")
     */
    public function list(SessionInterface $masession)
    {
        $info= $masession->get('info');
        $fournisseurs=$this->repository->findAll();
      
        
       
        return $this->render('fournisseur/list.html.twig', [
            'fournisseurs' => $fournisseurs,'info'=>$info
        ]);
    }
}
