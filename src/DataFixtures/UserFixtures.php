<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;

use App\Entity\Profile;
use App\Entity\Game;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProfileRepository;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    /* 
    * Add random profiles (same password)
    */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
  
        for ($i = 0; $i < 50; $i++) 
        {
            $profile = new Profile();
            $profile->setNick($faker->firstName());
            $profile->setEmail($faker->email());
            $profile->setPhone($faker->phoneNumber());
            $profile->setDateJoined(date_create($time = "now"));
            $profile->setPassword('0000');

            $manager->persist($profile);
            $manager->flush();
        }
        
    }

    public static function getGroups(): array
    {
        return ['gameGroup'];
    }
}
