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

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager, ): void
    {
        $faker = Factory::create();

        /* 
        * Add random profiles (same password)
        */
        /*
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
        }*/

        /* 
        * Add random games
        * winners and losers are picked from existing profiles
        */
        
        $arr = $manager->createQueryBuilder('profile')
        ->select('p.Nick')
        ->from('App\Entity\Profile', 'p')
        ->where('p.Nick is not NULL')
        ->getQuery()
        ->getArrayResult();

        $nickArray = array_map(function($a){ return $a['Nick']; }, $arr);

        for ($i = 0; $i < 100; $i++) 
        {
            $game = new Game();
            
            $game->setWinner($faker->randomElement($nickArray));
            $game->setLoser($faker->randomElement($nickArray));
            $game->setWinnerColor($faker->randomElement(['white', 'black']));

            if($game->getWinnerColor() == 'white')
            {
                $game->setLoserColor('black');
            }
            else
            {
                $game->setLoserColor('white');
            }
            
            $game->setMoveCount(mt_rand(3, 75));
            $game->setDateTime(date_create($time = "now"));

            $winner = $manager->getRepository(Profile::class)->findOneBySomeField($game->getWinner());
            $loser = $manager->getRepository(Profile::class)->findOneBySomeField($game->getLoser());
            $winner->setWins($winner->getWins() + 1);
            $loser->setDefeats($winner->getDefeats() + 1);

            if($game->getWinnerColor() == 'white')
            {
                $winner->setWhiteWinrate($winner->getWhiteWinrate() + 1);
                $winner->setWhiteGames($winner->getWhiteGames() + 1);
                $loser->setBlackGames($loser->getWhiteGames() + 1);
            }
            else
            {
                $winner->setBlackWinrate($winner->getBlackWinrate() + 1);
                $winner->setBlackGames($winner->getBlackGames() + 1);
                $loser->setWhiteGames($loser->getWhiteGames() + 1);
            }

            $manager->persist($game);
            $manager->persist($winner);
            $manager->persist($loser);
            $manager->flush();
        }
        
    }
}
