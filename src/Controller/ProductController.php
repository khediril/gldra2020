<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;

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
     * @Route("/show/{id}", name="show")
     */
    public function show($id)
    {
        $etudiants=
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

}
