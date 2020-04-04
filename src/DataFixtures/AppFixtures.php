<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Product;
use Faker\Factory;
use App\Entity\Fournisseur;
use App\Repository\FournisseurRepository;

class AppFixtures extends Fixture
{
    private $frepo;
    public function __construct(FournisseurRepository $f)
    {
        $this->frepo=$f;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        
        
        for ($i = 0; $i < 6; $i++) {
            $categ = new Category();
            $categ->setName("categorie" . $i);
            $manager->persist($categ);
            $limit=rand(5,10);
            for ($j = 0; $j < $limit; $j++) {
                $product = new Product();
                $product->setName("Produit" . $j);
                $product->setPrice(100*$j);
                $product->setDescription($faker->address);
                $product->setCategory($categ);
    
                $manager->persist($product);
                $manager->flush();
                //var_dump($product);
                //echo "******************/n";
                
                
            }
            for($k=0;$k<5;$k++)
                {
                    $four=new Fournisseur();
                    $four->setName($faker->name);
                    $four->setAdresse($faker->address);
                    //var_dump($four->getProducts());
                    //$four->addProduct($product);
                    $manager->persist($four);
                   // $product->addFournisseur($four);
                   // $manager->persist($product);
                }
               
            
           
        }
       // $manager->flush();
       // $prods=$manager->getRepository(Product::class)->findAll();
        //var_dump($prods[0]);
        //       foreach($prods as $key=>$p)
        //       {
        //          
        //               $four= $this->frepo->find(rand(1,30));
        //               var_dump($four);
        //               echo("/n*********************************/n");
        //               $p->addFournisseur($four);
                  

        //       } 

               $manager->flush();
    }
}
