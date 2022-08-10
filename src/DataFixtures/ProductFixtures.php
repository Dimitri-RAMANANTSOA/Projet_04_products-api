<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Product;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for($i = 0; $i < 100; $i++)
        {
           $product = new Product();
           $product
           ->setMpn($faker->ean13())
           ->setName($faker->word())
           ->setDescription($faker->bs())
           ->setIssueAt($faker->dateTimeThisDecade())
           ;

           $manager->persist($product);
        }

        $manager->flush();
    }
}
