<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Product;
use Faker\Factory;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        
        for ($i = 0; $i < 20; $i++) {
            $product = new Product();
            $product->setName("Produit" . $i);
            $product->setPrice(100*$i);
            $product->setDescription($faker->address);

            $manager->persist($product);
        }
        for ($i = 0; $i < 6; $i++) {
            $categ = new Category();
            $categ->setName("categorie" . $i);
            

            $manager->persist($categ);
        }

       


        $manager->flush();
    }
}
