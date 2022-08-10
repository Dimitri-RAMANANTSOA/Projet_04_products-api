<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Manufacturer;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ManufacturerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for($i = 0; $i < 100; $i++)
        {
           $manufacturer = new Manufacturer();
           $manufacturer
           ->setName($faker->company())
           ->setDescription($faker->catchPhrase())
           ->setCountryCode($faker->countryCode())
           ->setListedAt($faker->dateTimeThisDecade())
           ;

           $manager->persist($manufacturer);
        }

        $manager->flush();
    }
}
