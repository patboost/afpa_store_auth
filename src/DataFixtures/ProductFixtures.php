<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Command\SecretsSetCommand;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($c=1; $c<=5; $c++)
        {
            // Creation categorie
            // ******************
            $cat = new Category();
            $cat->setName($faker->word());
            $manager->persist($cat);

            // Creation produits
            // *****************
            for($p=1; $p<= mt_rand(3, 6); $p++)
            {
                $prod = new Product();
                $prod->setName($faker->word())
                    ->setPrice($faker->randomFloat(2, 1, 1000))
                    ->setQuantity($faker->numberBetween(1, 1000))
                    ->setCategory($cat);
                $manager->persist($prod);
            }
        }
        $manager->flush();
    }
}
