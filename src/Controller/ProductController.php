<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    private $repo ;

    public function __construct(ProductRepository $r)
    {
       $this->repo=$r; 
    }  
    
    /**
     * @Route("/add", name="product_add")
     */
    public function add()
    {
        $produit=new Product();

        $produit->setName("tomate");
        $produit->setPrice(1200);
        $produit->setDescription("Description de tomate");
       
        $em=$this->getDoctrine()->getManager();
      
        $em->persist($produit);
        $em->flush();
       
        return $this->render('product/add.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }
    /**
     * @Route("/add/{name}/{price}/{description}", name="product_add2")
     */
    public function add2($name,$price,$description)
    {
        $produit=new Product();

        $produit->setName($name);
        $produit->setPrice($price);
        $produit->setDescription($description);
       
        $em=$this->getDoctrine()->getManager();
      
        $em->persist($produit);
        $em->flush();
       
        return $this->render('product/add.html.twig', [
            'produit' => $produit,
        ]);
    
    }
     /**
     * @Route("/list", name="product.list")
     */
    public function list()
    {
        $produits=$this->getDoctrine()->getRepository(Product::class)->findAll();
      
        
       
        return $this->render('product/list.html.twig', [
            'produits' => $produits,
        ]);
    }
    /**
     * @Route("/byprice/{min}/{max}", name="product_byprice")
     */
    public function listByPrice($min,$max)
    {
        $produits=$this->repo->findByPriceRange($min,$max);
         
        return $this->render('product/list.html.twig', [
            'produits' => $produits,
        ]);
    }
     /**
     * @Route("/show/{id}", name="produit.show")
     */
    public function show($id)
    {
        
        $produit=$this->getDoctrine()->getRepository(Product::class)->find($id);
      
        if (!$produit) {
            throw $this->createNotFoundException(
                'Le produit de id :   '.$id. 'est inexistant...'
            );
        }
       
        return $this->render('product/show.html.twig', [
            'produit' => $produit,
        ]);
    }
    /**
     * @Route("/delete/{id}", name="product_delete")
     */
    public function delete($id)
    {
        $produit=$this->getDoctrine()->getRepository(Product::class)->find($id);
      
        if (!$produit) {
            throw $this->createNotFoundException(
                'Le produit de id :   '.$id. 'est inexistant...'
            );
        }
        $em=$this->getDoctrine()->getManager();
        $em->remove($produit);
        $em->flush();
        return $this->redirectToRoute('product.list');
    }
    /**
     * @Route("/update/{id}/{description}", name="product_update")
     */
    public function update($id,$description)
    {
        $produit=$this->getDoctrine()->getRepository(Product::class)->find($id);
      
        if (!$produit) {
            throw $this->createNotFoundException(
                'Le produit de id :   '.$id. 'est inexistant...'
            );
        }
        $em=$this->getDoctrine()->getManager();
        $produit->setDescription($description);
        $em->persist($produit);
        $em->flush();
        return $this->redirectToRoute('product.list');
    }
    /**
     * @Route("/formadd", name="product_formadd")
     */
    public function formadd(Request $request)
    {
        $produit=new Product();

        $form = $this->createFormBuilder($produit)
            ->add('name', TextType::class)
            ->add('price', IntegerType::class)
            ->add('description', TextareaType::class)
            ->add('category', EntityType::class, [
                // looks for choices from this entity
                'class' => Category::class,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'name',
            
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            ->add('save', SubmitType::class, ['label' => 'Ajouter'])
            ->getForm();
       
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // $form->getData() holds the submitted values
                // but, the original `$task` variable has also been updated
                //$task = $form->getData();
        
                // ... perform some action, such as saving the task to the database
                // for example, if Task is a Doctrine entity, save it!
                 $entityManager = $this->getDoctrine()->getManager();
                 $entityManager->persist($produit);
                 $entityManager->flush();
        
                return $this->redirectToRoute('product.list');
            }
       
        return $this->render('product/formadd.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

}
