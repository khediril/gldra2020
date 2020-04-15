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
    public function load(ObjectManager $manager)
    {
        $names=['c1','c2','légumes','vetement','c3','c4'];
        $faker = Factory::create();
     
        for ($i = 0; $i < 6; $i++)
        {
            $categ = new Category();
            $categ->setName($names[$i]);
            $manager->persist($categ);
            $limit=rand(5,10);
            for ($j = 0; $j < $limit; $j++)
            {
                $product = new Product();
                $product->setName("Produit".$j);
                $product->setPrice(100*$j);
                $product->setDescription($faker->address);
                $product->setCategory($categ);
                $manager->persist($product);  

                $limit = rand(1,5);
                for($k=0;$k<$limit;$k++)
                {
                    $four=new Fournisseur();
                    $four->setName($faker->name);
                    $four->setAdresse($faker->address);           
                    $manager->persist($four); 
                    $product->addFournisseur($four);
                    $manager->persist($product); 
                   
                }   
            } 
        }
        $manager->flush();

                   






    }
}
