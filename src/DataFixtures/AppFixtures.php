<?php

namespace App\DataFixtures;

use App\Entity\Animal;
use App\Entity\Country;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $countries = Array();
        for ($i = 0; $i < 10; $i++) {
            $countries[$i] = new Country();
            $countries[$i]->setName($faker->country);
            $countries[$i]->setIso($faker->countryCode);
            $manager->persist($countries[$i]);
        }
        $animals = Array();
        for ($i = 0; $i < 10; $i++) {
            $animals[$i] = new Animal();
            $animals[$i]->setName($faker->firstName);
            $animals[$i]->setHeight($faker->numberBetween($min = 10, $max = 600));
            $idC =rand(1,count($countries));
            $animals[$i]->setCountry($idC);
            $animals[$i]->setLifetime($faker->numberBetween($min, $max = 120));
            $animals[$i]->setMartialArt($faker->word);
            $animals[$i]->setTelephone($faker->phoneNumber);
            $manager->persist($animals[$i]);
        }
        $manager->flush();
    }
}
